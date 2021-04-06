<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Query\ResultSetMapping;

use App\Models\Entity\ {
    Tribo,
    Jogador,
    Estacao,
    Desafio
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
    
   public function atualizarTriboDesafio($id_tribo, Desafio $desafio){
        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->find($id_tribo);
        
        $tribo->moedas += $desafio->moedas;
        $tribo->sabedoria += $desafio->sabedoria;
        $tribo->experiencia += $desafio->xp;
        $this->entityManager->flush();     
   }

   public function consultarTriboPorId(Request $request, Response $response, array $args) : Response{
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

    public function consultarTriboPorUsuario(Request $request, Response $response, array $args) : Response{
        $usuario = strtolower($args['nick_name']);
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Jogador');
        $jogador = $repository->findOneBy(array('nick_name' => $usuario));
        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->findOneBy(array('id_jogador' => $jogador->id_jogador));

        if (!$tribo) {
            $this->logger->warning("Tribo {$usuario} Not Found");
            throw new \Exception("Tribo not Found para o jogador{$jogador->id_jogador}", 404);
        }       

        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao');
        $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));

        $response->getBody()->write(json_encode($tribo,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
    public function atualizarMoedas(Request $request, Response $response, array $args) : Response{
        $rsm = new ResultSetMapping;
        $sp = "call prc_atualizar_moedas;";
        $query = $this->entityManager->createNativeQuery($sp,$rsm);
        $result = $query->getResult();

        $response->getBody()->write(json_encode('sucess:true',  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
}