<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Estacao_tipo;

final class EstacaoTipoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarEstacaoTipoPorId(Request $request, Response $response, array $args) : Response{
        $id_estacao_Tipo = $args['id_estacao_Tipo'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Estacao_tipo');
        $estacao_Tipo = $repository->find($id_estacao_Tipo);
        
        if (!$estacao_Tipo) {
            $this->logger = $this->get('logger');
            $this->logger->warning("EstacaoTipo {$id_estacao_Tipo} Not Found");
            throw new \Exception("EstacaoTipo {$id_estacao_Tipo} not Found", 404);
        }       

        $response->getBody()->write(json_encode($estacao_Tipo,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}