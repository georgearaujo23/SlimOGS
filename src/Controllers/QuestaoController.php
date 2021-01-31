<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Questao;

final class QuestaoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarQuestaoParaDesafio(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();        
        $qb = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from('App\Models\Entity\Questao', 'u')
                ->where('NOT EXISTS ('
                        . 'SELECT 1 FROM App\Models\Entity\Questao_desafio_jogador d '
                        . 'where d.id_questao = u.id_questao AND d.id_desafio_jogador = ?1)'
                        )
                ->orderBy('rand()')
                ->setMaxResults($params->quantidade_questoes)
                ->setParameter(1, $params->id_desafio_jogador);
        $query = $qb->getQuery();
        $questoes = $query->getResult(); 

        if (!$questoes) {
            $this->logger->warning("Questao not Found para o id_desafio_jogador {$params->id_desafio_jogador}");
            $response->getBody()->write('{ "questoes": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }
        
        $alternativasController = new QuestaoAlternativaController($this->container);
        foreach($questoes as $questao){
            $alternativas = $alternativasController->consultarAlternativasPorIdQuestao($questao->id_questao);
            $questao->setAlternativas($alternativas);
        }

        $response->getBody()->write('{ "questoes": ' . json_encode($questoes,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
    public function consultarQuestaoPorId(Request $request, Response $response, array $args) : Response{
        $id_questao = $args['id_questao'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Questao');
        $questao = $repository->find($id_questao);

        $alternativasController = new QuestaoAlternativaController($this->container);
        $alternativas = $alternativasController->consultarAlternativasPorIdQuestao($questao->id_questao);
        $questao->setAlternativas($alternativas);

        $response->getBody()->write(json_encode($questao,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}