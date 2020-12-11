<?php
require '../vendor/autoload.php';

use Psr7Middlewares\Middleware\TrailingSlash;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;

$isDevMode = true;
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = 'localhost';
$config['db']['user']   = 'root';
$config['db']['pass']   = 'abc123';
$config['db']['dbname'] = 'ogs';

/**
 * Cria nosso APP
 */
$app = new \Slim\App(['settings' => $config]);

/**
 * Ignora diferença entre url/teste e url/teste/
 */
$app->add(new TrailingSlash(false));

/**
 * Cria o container para aplicação
 */
$container = $app->getContainer();

/**
 * Token do nosso JWT
 */
$container['secretkey'] = "secretloko";

/**
 * Cria rota de autenticação
 */
$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    "path" => "/auth", /* or ["/admin", "/api"] */
    "realm" => "Protected",
    "users" => [
        "root" => "t00r",
        "somebody" => "passw0rd"
    ]
]));


/**
 * Auth básica do JWT
 * Whitelist - Bloqueia tudo, e só libera os
 * itens dentro do "ignore"
 */
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "regexp" => "/(.*)/", //Regex para encontrar o Token nos Headers - Livre
    "header" => "X-Token", //O Header que vai conter o token
    "path" => "/", //Vamos cobrir toda a API a partir do /
    "ignore" => "/auth",
    "realm" => "Protected", 
    "secret" => $container['secretkey'] //Nosso secretkey criado 
]));

/**
 * Criar log da api
 */
$container['logger'] = function($container) {
    $logger = new Monolog\Logger('OGS-microservice');
    $logfile = 'C:/Apache24/htdocs/SlimOGS/logs/OGS-microservice.log';
    $stream = new Monolog\Handler\StreamHandler($logfile, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream, Monolog\Logger::INFO);
    $logger->pushHandler($fingersCrossed);
    return $logger;
};

$container['db_Connect'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

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
 * Instância do Entity Manager
 * Coloca o Entity manager dentro do container com o nome de em (Entity Manager)
 */
$container['em'] = EntityManager::create($dbParams, $config);





