<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Tribo,
    Jogador,
    Estacao
};

final class TriboController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
    public function triboPorId(Request $request, Response $response, array $args) : Response{
        $id_tribo = $args['id_tribo'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->find($id_tribo);
        /**
         * Verifica se existe a tribo com a ID informada
         */
        if (!$tribo) {
            $this->logger->warning("Tribo {$id_tribo} Not Found");
            throw new \Exception("Tribo not Found", 404);
        }       

        $response->getBody()->write(json_encode($tribo,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }

    public function triboPorUsuario(Request $request, Response $response, array $args) : Response{
        $usuario = strtolower($args['usuario']);
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Jogador');
        $jogador = $repository->findOneBy(array('nick_name' => $usuario));
        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->findOneBy(array('id_jogador' => $jogador->id_jogador));
        /**
         * Verifica se existe a tribo com a ID informada
         */
        if (!$tribo) {
            $this->logger->warning("Tribo {$id_tribo} Not Found");
            throw new \Exception("Tribo not Found para o jogador{$jogador->id_jogador}", 404);
        }       

        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao');
        $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));

        $response->getBody()->write(json_encode($tribo,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
}