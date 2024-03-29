<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Estacao_melhoria;

final class EstacaoMelhoriaController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarEstacaomelhoriaPorSabedoria(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();
        $id_estacao_tipo = $params->id_estacao_tipo;
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
        $tribo = $repository->find($params->id_tribo);
        
        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Estacao_melhoria u '
                . 'WHERE u.id_estacao_tipo = ?1'
                . 'AND u.pesquisado = 1');
        $query->setParameter(1, $id_estacao_tipo);
        $estacao_melhoria = $query->getResult(); 

        if (!$estacao_melhoria) {
            $this->logger->warning("EstaçãoMelhoria not Found para tipo estação {$id_estacao_tipo}");
            $response->getBody()->write('{ "melhorias": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }

        foreach ($estacao_melhoria as $em){
            $em->custo_moedas *= $tribo->nivel;
        }
        
        $response->getBody()->write('{ "melhorias": ' . json_encode($estacao_melhoria,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}