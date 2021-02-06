<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class VersaoApkController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
   public function consultarVersaoAtual(Request $request, Response $response, array $args) : Response{
        $repository = $this->entityManager->getRepository('App\Models\Entity\Versao_apk');
        $versao_apk = $repository->findOneBy(array('ativo' => true));
        
        if (!$versao_apk) {
            $this->logger->warning("Versao nao encontrada Not Found");
            throw new \Exception("Versão not Found}", 404);
        }       

        $response->getBody()->write(json_encode($versao_apk,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
}