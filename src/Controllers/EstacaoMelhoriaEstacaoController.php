<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Estacao_melhoria_estacao
};

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

    public function consultarEstacaoMelhoriaEmConstrucao(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();
        $id_estacao = $params->id_estacao;

        $query = $this->entityManager->createQuery('SELECT u, e FROM App\Models\Entity\Estacao_melhoria_estacao u '
                . 'JOIN u.estacao_melhoria e '
                . 'WHERE u.estaConstruindo = 1 AND u.id_estacao = ?1');
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
            $estacaoMelhoriaEstacao->estaConstruindo = true;
            $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria');
            $estacao_melhoria = $repository->find($params->id_estacao_melhoria);
            $estacaoMelhoriaEstacao->estacao_melhoria = $estacao_melhoria;
            //var_dump("aqui");die;
            /**
            * Persiste a entidade no banco de dados
            */
           $this->entityManager->persist($estacaoMelhoriaEstacao);
           $this->entityManager->flush();
        }else{
            $estacaoMelhoriaEstacao->quantidade += 1;
            $estacaoMelhoriaEstacao->estaConstruindo = true;
            $this->entityManager->flush();
        }

        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->find($params->id_tribo);

        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao');
        $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));


        /**
         * Verifica se existe a tribo com a ID informada
         */
        if (!$tribo) {
            $this->logger->warning("Tribo {$params->id_tribo} Not Found");
            throw new \Exception("Tribo not Found", 404);
        }       
        $return = $response->withJson($tribo, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }

    public function atualizarEstacaoMelhoriaEstacaoConstrucao(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_melhoria_estacao');
        $estacaoMelhoriaEstacao = $repository->findOneBy(
                array('id_estacao_melhoria_estacao' => $params->id_estacao_melhoria_estacao)
        );

        if ($estacaoMelhoriaEstacao) {
            if(date("Y-m-d H:i:s") >= $estacaoMelhoriaEstacao->fimConstrucao){
                $estacaoMelhoriaEstacao->estaConstruindo = 0;
                $estacaoMelhoriaEstacao->quantidade += 1;
                $this->entityManager->flush();
            }
        }

        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->find($params->id_tribo);

        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao');
        $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));


        /**
         * Verifica se existe a tribo com a ID informada
         */
        if (!$tribo) {
            $this->logger->warning("Tribo {$params->id_tribo} Not Found");
            throw new \Exception("Tribo not Found", 404);
        }       
        $return = $response->withJson($tribo, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }
    
}