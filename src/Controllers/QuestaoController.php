<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Questao
};

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
    
    public function consultarQuestaoPorId(Request $request, Response $response, array $args) : Response{
        
        $id_questao = $args['id_questao'];
        $this->entityManager = $this->get('em');

        $repository = $this->entityManager->getRepository('App\Models\Entity\Questao');
        $questao = $repository->find($id_questao);

        $repositoryAlternativas = $this->entityManager->getRepository('App\Models\Entity\Questao_alternativa');
        $alternativas = $repositoryAlternativas->findBy(array('id_questao' => $id_questao));
        $questao->setAlternativas($alternativas);

        $response->getBody()->write(json_encode($questao,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
    public function consultarQuestaoParaDesafio(Request $request, Response $response, array $args) : Response{
         
        $quantidade_questoes = $args['quantidade_questoes'];
        $id_desafio_jogador = $args['id_desafio_jogador'];
        $qb = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from('App\Models\Entity\Questao', 'u')
                ->where('NOT EXISTS ('
                        . 'SELECT 1 FROM App\Models\Entity\Questao_desafio_jogador d '
                        . 'where d.id_questao = u.id_questao AND d.id_desafio_jogador = ?1)'
                        )
                ->orderBy('rand()')
                ->setMaxResults(1)
                ->setParameter(1, 1);
        $query = $qb->getQuery();
        $questoes = $query->getResult(); 

        if (!$questoes) {
            $this->logger->warning("Questao not Found para o id_desafio_jogador {$id_desafio_jogador}");
            $response->getBody()->write('{ "questoes": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }
        
        $repositoryAlternativas = $this->entityManager->getRepository('App\Models\Entity\Questao_alternativa');
        foreach($questoes as $questao){    
            $alternativas = $repositoryAlternativas->findBy(array('id_questao' => $questao->id_questao));
            $questao->setAlternativas($alternativas);

        }

        $response->getBody()->write('{ "questoes": ' . json_encode($questoes,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
}