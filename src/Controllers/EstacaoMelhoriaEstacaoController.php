<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Estacao_melhoria_estacao;

final class EstacaoMelhoriaEstacaoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }

    public function atualizarEstacaoMelhoriaEstacaoConstrucao(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria_estacao');
        $estacaoMelhoriaEstacao = $repository->findOneBy(
                array('id_estacao_melhoria_estacao' => $params->id_estacao_melhoria_estacao)
        );

        if ($estacaoMelhoriaEstacao) {
            if(date("Y-m-d H:i:s") >= $estacaoMelhoriaEstacao->fim_construcao){
                $estacaoMelhoriaEstacao->esta_construindo = 0;
                $estacaoMelhoriaEstacao->quantidade += 1;
                $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria');
                $estacaoMelhoria = $repository->find($estacaoMelhoriaEstacao->id_estacao_melhoria);
                if(!is_null($estacaoMelhoria->id_estacao_melhoria_relacionada)){
                    $estacaoMelhoria->pesquisado = false;
                    $this->entityManager->flush();
                    $estacaoMelhoria = $repository->find($estacaoMelhoria->id_estacao_melhoria_relacionada);
                    $estacaoMelhoria->pesquisado = true;
                }
                $this->entityManager->flush();
            }
        }
        
        $triboController = new TriboController($this->container);
        return $triboController->consultarTriboPorUsuario($request, $response, array('nick_name' => $params->nick_name));       
    }

    public function consultarEstacaoMelhoriaEmConstrucao(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();
        $id_estacao = $params->id_estacao;

        $query = $this->entityManager->createQuery('SELECT u, e FROM App\Models\Entity\Estacao_melhoria_estacao u '
                . 'JOIN u.estacao_melhoria e '
                . 'WHERE u.esta_construindo = 1 '
                . 'AND u.id_estacao = ?1');
        $query->setParameter(1, $id_estacao);
        $estacao_melhoria_estacao = $query->getResult(); 

        if (!$estacao_melhoria_estacao) {
            $this->logger->warning("EstaçãoMelhoriaEstacao not Found para id_estacao {$id_estacao}");
            $response->getBody()->write('{ }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(402);
        }

        $response->getBody()->write(json_encode($estacao_melhoria_estacao[0],  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function consultarEstacaomelhoriaEstacaoPorEstacao(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();
        $id_estacao = $params->id_estacao;

        $query = $this->entityManager->createQuery('SELECT u, e FROM App\Models\Entity\Estacao_melhoria_estacao u '
                . 'JOIN u.estacao_melhoria e '
                . 'WHERE u.id_estacao = ?1');
        $query->setParameter(1, $id_estacao);
        $estacao_melhoria_estacao = $query->getResult(); 

        if (!$estacao_melhoria_estacao) {
            $this->logger->warning("EstaçãoMelhoriaEstacao not Found para id_estacao {$id_estacao}");
            $response->getBody()->write('{ "melhorias": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }

        $response->getBody()->write('{ "melhorias": ' . json_encode($estacao_melhoria_estacao,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function inserirEstacaomelhoriaEstacao(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria_estacao');
        $estacaoMelhoriaEstacao = $repository->findOneBy(
                array('id_estacao' => $params->id_estacao, 
                    'id_estacao_melhoria' => $params->id_estacao_melhoria)
                );

        if (!$estacaoMelhoriaEstacao) {
            $estacaoMelhoriaEstacao = new Estacao_melhoria_estacao();
            $estacaoMelhoriaEstacao->id_estacao = $params->id_estacao;
            $estacaoMelhoriaEstacao->id_estacao_melhoria = $params->id_estacao_melhoria;
            $estacaoMelhoriaEstacao->quantidade = 0;
            $estacaoMelhoriaEstacao->esta_construindo = true;
            $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria');
            $estacao_melhoria = $repository->find($params->id_estacao_melhoria);
            $estacaoMelhoriaEstacao->estacao_melhoria = $estacao_melhoria;
            $this->entityManager->persist($estacaoMelhoriaEstacao);
            $this->entityManager->flush();
        }else{
            $estacaoMelhoriaEstacao->esta_construindo = true;
            $this->entityManager->flush();
        }
        $triboController = new TriboController($this->container);
        return $triboController->consultarTriboPorUsuario($request, $response, array('nick_name' => $params->nick_name));
    }
    
}