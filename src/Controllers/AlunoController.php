<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Aluno;

final class AlunoController {
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarAlunoPorId(Request $request, Response $response, array $args) : Response{
        $id_aluno = $args['id_aluno'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Aluno');
        $aluno = $repository->find($id_aluno);

        $response->getBody()->write(json_encode($aluno,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}