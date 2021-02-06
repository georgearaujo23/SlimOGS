<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\ {
    Desafio,
    Questao_desafio_jogador
};

final class QuestaoDesafioJogadorController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function inserirQuestaoDesafioJogador(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        $qDesafioJogador = new Questao_desafio_jogador();
        $qDesafioJogador->id_desafio_jogador = $params->id_desafio_jogador;
        $qDesafioJogador->id_questao = $params->id_questao;
        $qDesafioJogador->id_questao_alternativa = $params->id_questao_alternativa;
        $qDesafioJogador->data_resposta = date("Y-m-d H:i:s");

        $this->entityManager->persist($qDesafioJogador);
        $this->entityManager->flush();

        $desafioJogadorController = new DesafioJogadorController($this->container);
        $desafioJogador = $desafioJogadorController->AtualizarDesafioJogador($params->id_desafio_jogador, $params->acertou, $params->terminou);
        $triboController = new TriboController($this->container);
        $desafioController = new DesafioController($this->container);
        $desafio = $desafioController->consultarDesafioPorId($params->id_desafio);
        if($params->terminou && $desafioJogador->quantidade_acertos > $desafio->quantidade_acertos){
            $triboController->atualizarTriboDesafio($params->id_tribo, $desafio);
        }
        return $triboController->consultarTriboPorUsuario($request, $response, array('nick_name' => $params->nick_name));       
    }
}