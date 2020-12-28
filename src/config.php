<?php
require '../vendor/autoload.php';
require '../classes/RandomAuthenticator.php';
use Psr7Middlewares\Middleware\TrailingSlash;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Firebase\JWT\JWT;
use Slim\Slim;
use Slim\Middleware\SessionCookie;
use App\Auth\Auth;
use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface;
use App\Models\Entity\Jogador;

$isDevMode = true;
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

/**
 * Cria nosso APP
 */
$app = new \Slim\App(['settings' => $config]);

/**
 * Diret�rio de Entidades e Metadata do Doctrine
 */
$config = Setup::createAnnotationMetadataConfiguration(array("C:/Apache24/htdocs/SlimOGS/src/Models/Entity"), $isDevMode);

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => 'abc123',
    'dbname'   => 'ogs',
);

/**
 * Cria o container para aplica��o
 */
$container = $app->getContainer();


/**
 * Inst�ncia do Entity Manager
 * Coloca o Entity manager dentro do container com o nome de em (Entity Manager)
 */
$container['em'] = EntityManager::create($dbParams, $config);


/**
 * Ignora diferen�a entre url/teste e url/teste/
 */
$app->add(new TrailingSlash(false));

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

/**
 * Converte os Exceptions Gen�ricas dentro da Aplica��o em respostas JSON
 */
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;
        return $container['response']->withStatus($statusCode)
            ->withHeader('Content-Type', 'Application/json')
            ->withJson(["message" => $exception->getMessage()], $statusCode);
    };
};

/**
 * Token do nosso JWT
 */
$container['secretkey'] = "secretloko";

/*
/**
 * Cria rota de autentica��o
 */
$app->add(new Tuupola\Middleware\HttpBasicAuthentication([
    
    "path" => "/auth",
    "realm" => "Protected",
    "secure" => true,
    "authenticator" => new RandomAuthenticator($container['em'], $container['logger']),
    "error" => function ($response, $arguments) {
        $data = [];
        $data["status"] = "error";
        $data["message"] = $arguments["message"];

        $body = $response->getBody();
        $body->write(json_encode($data, JSON_UNESCAPED_SLASHES));

        return $response->withBody($body);
    }
]));


/**
 * Auth b�sica do JWT
 * Whitelist - Bloqueia tudo, e s� libera os
 * itens dentro do "ignore"
 */
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "regexp" => "/(.*)/", //Regex para encontrar o Token nos Headers - Livre
    "header" => "X-Token", //O Header que vai conter o token
    "path" => "/", //Vamos cobrir toda a API a partir do /
    "ignore" => "/auth",
    "realm" => "Protected", 
    "secret" => $container['secretkey']
]));
