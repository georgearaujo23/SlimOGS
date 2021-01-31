<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Questao_alternativa;

final class QuestaoAlternativaController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarAlternativasPorIdQuestao($id_questao){
        $repository = $this->entityManager->getRepository('App\Models\Entity\Questao_alternativa');
        return $repository->findBy(array('id_questao' => $id_questao));
    }
    
    public function consultarQuestaoAlternativaPorId(Request $request, Response $response, array $args) : Response{
        $id_questao_alternativa = $args['id_questao_alternativa'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Questao_alternativa');
        $questao_alternativa = $repository->find($id_questao_alternativa);

        $response->getBody()->write(json_encode($questao_alternativa,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}