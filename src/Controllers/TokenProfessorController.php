<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Models\Entity\Token_professor;

final class TokenProfessorController {
    protected $container;
    protected $entityManager;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $this ->container['em'];
   }
    
    public function consultarPorRefreshToken($refreshToken){
        $repository = $this->entityManager->getRepository('App\Models\Entity\Token_professor');
        return $repository->findOneBy(array('refresh_token' => $refreshToken));   
    }
    
    public function inserirTokenProfessor($token, $refreshToken,  $validade, $id_professor): bool{
        $token_professor = new Token_professor();
        $token_professor->token = $token;
        $token_professor->refresh_token = $refreshToken;
        $token_professor->validade = $validade;
        $token_professor->id_professor = $id_professor;
        $this->entityManager->persist($token_professor);
        $this->entityManager->flush();
        return true;
    }
}

