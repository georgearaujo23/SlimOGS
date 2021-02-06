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
    
    public function AtualizarDesafioJogador($id_desafio_jogador, $acertou, $terminou) {

        $repository = $this->entityManager->getRepository('App\Models\Entity\Desafio_jogador');
        $desafioJogador = $repository->find($id_desafio_jogador);
        
        $desafioJogador->terminou = $terminou;
        $desafioJogador->quantidade_respondida = $desafioJogador->quantidade_respondida + 1;
        $desafioJogador->quantidade_acertos = $desafioJogador->quantidade_acertos + $acertou;
        $this->entityManager->flush();  
        return $desafioJogador;
    }
    
    public function inserirDesafioJogador(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        $repository = $this->entityManager->getRepository('App\Models\Entity\Desafio_jogador');
        $desafioJogador = $repository->findOneBy(array('id_jogador' => $params->id_jogador, 'id_desafio' => $params->id_desafio));
        
        if(!$desafioJogador){
            $desafioJogador = new Desafio_jogador();
            $desafioJogador->id_jogador = $params->id_jogador;
            $desafioJogador->id_desafio = $params->id_desafio;
            $desafioJogador->quantidade_acertos = 0;
            $desafioJogador->terminou = false;
            $desafioJogador->quantidade_respondida = 0;

            $this->entityManager->persist($desafioJogador);
            $this->entityManager->flush();
        }

        $return = $response->withJson($desafioJogador, 200)
            ->withHeader('Content-type', 'application/json');
        return $return;       
    }
}