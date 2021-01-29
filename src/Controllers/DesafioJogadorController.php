<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Desafio,
    Desafio_jogador
};

final class DesafioJogadorController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
    public function incluirDesafioJogador(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        $desafioJogador = new Desafio_jogador();
        $desafioJogador->id_jogador = $params->id_jogador;
        $desafioJogador->id_desafio = $params->id_desafio;
        $desafioJogador->quantidade_acertos = 0;
        $desafioJogador->data_resposta = date("Y-m-d H:i:s");
        
        $this->entityManager->persist($desafioJogador);
        $this->entityManager->flush();

        $return = $response->withJson($desafioJogador, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }
   
}