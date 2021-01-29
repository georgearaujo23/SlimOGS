<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Jogador
};

final class JogadorController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
    public function consultarJogadorPorId(Request $request, Response $response, array $args) : Response{
        $id_jogador = $args['id_jogador'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Jogador');
        $jogador = $repository->find($id_jogador);
        /**
         * Verifica se existe a tribo com a ID informada
         */
        if (!$jogador) {
            $this->logger->warning("Jogador {$id_jogador} Not Found");
            throw new \Exception("Jogador {$id_jogador} not Found", 404);
        }       

        $response->getBody()->write(json_encode($jogador,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
   
}