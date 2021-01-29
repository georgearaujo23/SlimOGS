<?php
require '../autoload.php';
use Psr7Middlewares\Middleware\TrailingSlash;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Slim\Slim;
use Slim\Middleware\SessionCookie;
use App\Auth\Auth;
$isDevMode = true;
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/**
 * Cria nosso APP
 */
$app = new \Slim\App(['settings' => $config]);

/**
 * Diretório de Entidades e Metadata do Doctrine
 */
$config = Setup::createAnnotationMetadataConfiguration(array("C:/Apache24/htdocs/SlimOGS/src/Models/Entity"), $isDevMode);

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'abc123',
    'dbname'   => 'ogs',
);

/**
 * Cria o container para aplicação
 */
$container = $app->getContainer();


/**
 * Instância do Entity Manager
 * Coloca o Entity manager dentro do container com o nome de em (Entity Manager)
 */
$container['em'] = EntityManager::create($dbParams, $config);
