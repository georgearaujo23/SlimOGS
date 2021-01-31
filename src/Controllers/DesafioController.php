<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\ {
    Desafio,
    Desafio_jogador
};

final class DesafioController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarDesafios(Request $request, Response $response, array $args) : Response{
        $id_jogador = $args['id_jogador'];
        
        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Desafio u '
                . 'WHERE u.data_inicio <= ?1'
                . 'AND u.data_fim >= ?1'
                . 'AND NOT EXISTS ('
                . 'SELECT 1 FROM App\Models\Entity\Desafio_jogador d '
                . 'WHERE d.terminou = 1 AND d.id_desafio = u.id_desafio AND d.id_jogador = ?2)');
        $query->setParameter(1, date("Y-m-d H:i:s"));
        $query->setParameter(2, $id_jogador);
        $desafios = $query->getResult(); 

        if (!$desafios) {
            $this->logger->warning("Desafios not Found para o jogador {$id_jogador}");
            $response->getBody()->write('{ "desafios": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }

        $response->getBody()->write('{ "desafios": ' . json_encode($desafios,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function consultarDesafioPorId($id_desafio){
        $repository = $this->entityManager->getRepository('App\Models\Entity\Desafio');
        return $repository->find($id_desafio);
    }
}