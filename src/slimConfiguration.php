<?php
namespace src;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use App\DQL\NumericFunction;

putenv('JWT_SECRET_KEY=secreOGSToCreateToken');

function slimConfiguration(): \Slim\Container
{
    $isDevMode = true;
    $dbParams = array(
        'driver'   => 'pdo_mysql',
        //'user'     => 'root',
        //'password' => 'abc123',
        //'dbname'   => 'ogs',
        'password' => 'Batata@ogs23',
        'user'     => 'u694554398_root',
        'dbname'   => 'u694554398_ogs',
    );
    
    /**
    * Diretório de Entidades e Metadata do Doctrine
    */
    $config = Setup::createAnnotationMetadataConfiguration(array($_SERVER['DOCUMENT_ROOT'] ."/src/Models/Entity"), $isDevMode);
   
    $config->addCustomNumericFunction('RAND', 'App\Models\Functions\Rand');
    
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
    * Converte os Exceptions Genéricas dentro da Aplicação em respostas JSON
    */
    $container['errorHandler'] = function ($container) {
       return function ($request, $response, $exception) use ($container) {
           $statusCode = $exception->getCode() ? $exception->getCode() : 500;
           $message = $exception->getMessage() ? $exception->getMessage() : "Erro Interno do servidor";
           return $container['response']->withStatus($statusCode)
               ->withHeader('Content-Type', 'Application/json')
               ->withJson(["message" => $message, "codErro" => $statusCode], $statusCode);
       };
   };
    
    return $container;
}