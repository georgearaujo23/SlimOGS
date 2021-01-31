<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Assunto;

final class AssuntoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarAssuntoPorId(Request $request, Response $response, array $args) : Response{
        $id_assunto = $args['id_assunto'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Assunto');
        $assunto = $repository->find($id_assunto);

        $response->getBody()->write(json_encode($assunto,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
}