<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;

final class EstacaoController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
    public function atualizarRecursos(Request $request, Response $response, array $args) : Response{
        $rsm = new ResultSetMapping;
        $sp = "call prc_atualizar_recursos;";
        $query = $this->entityManager->createNativeQuery($sp,$rsm);
        $result = $query->getResult();

        $response->getBody()->write(json_encode('sucess:true',  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
}

    
    
    