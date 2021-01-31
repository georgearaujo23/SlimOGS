<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Frequencia;

final class FrequenciaController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarFrequenciaPorId(Request $request, Response $response, array $args) : Response{
        $id_frequecia = $args['id_frequecia'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencia = $repository->find($id_frequecia);

        $response->getBody()->write(json_encode($frequencia,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
}