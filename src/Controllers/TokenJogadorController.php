<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

use App\Models\Entity\ {
    Token_jogador
};

final class TokenJogadorController {
    
    protected $container;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $this ->container['em'];
   }
    
    public function incluirTokenJogador($token, $refreshToken,  $validade, $id_jogador): bool{
        
        $token_jogador = new Token_jogador();
        $token_jogador->token = $token;
        $token_jogador->refresh_token = $refreshToken;
        $token_jogador->validade = $validade;
        $token_jogador->id_jogador = $id_jogador;
        
        $this->entityManager->persist($token_jogador);
        $this->entityManager->flush();
        return true;
    }
    
    
    public function consultarPorRefreshToken($refreshToken){
        $repository = $this->entityManager->getRepository('App\Models\Entity\Token_jogador');
        return $repository->findOneBy(array('refresh_token' => $refreshToken));
        
    }
    
}

