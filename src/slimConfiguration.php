<?php
namespace src;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


putenv('JWT_SECRET_KEY=secreOGSToCreateToken');

function slimConfiguration(): \Slim\Container
{
    $isDevMode = true;
    $dbParams = array(
        'driver'   => 'pdo_mysql',
        'user'     => 'root',
        'password' => 'abc123',
        //'password' => 'Abc@2323',
        'dbname'   => 'OGS',
    );
    
    /**
    * Diret�rio de Entidades e Metadata do Doctrine
    */
    //ifb
    //
    $config = Setup::createAnnotationMetadataConfiguration(array("C:/Apache24/htdocs/SlimOGS/src/Models/Entity"), $isDevMode);
    //Setup::createAnnotationMetadataConfiguration(array("/var/www/SlimOGS/src/Models/Entity"), $isDevMode);
   
    
    $configuration = [
        'settings' => [
            'displayErrorDetails' => true,
            'addContentLengthHeader' =>false ,
        ],
    ];
    
    $container = new \Slim\Container($configuration);
    $container['em'] = EntityManager::create($dbParams, $config);
    $container['secretkey'] = getenv('JWT_SECRET_KEY');
     /**
    * Converte os Exceptions Gen�ricas dentro da Aplica��o em respostas JSON
    */
    $container['errorHandler'] = function ($container) {
       return function ($request, $response, $exception) use ($container) {
           $statusCode = $exception->getCode() ? $exception->getCode() : 500;
           return $container['response']->withStatus($statusCode)
               ->withHeader('Content-Type', 'Application/json')
               ->withJson(["message" => $exception->getMessage(), "codErro" => $statusCode], $statusCode);
       };
   };
    
    return $container;
}