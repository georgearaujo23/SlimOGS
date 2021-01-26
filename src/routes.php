<?php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Psr7Middlewares\Middleware\TrailingSlash;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Tuupola\Middleware\JwtAuthentication;

use function src\{
    slimConfiguration,
    jwtAuth
};
use App\Models\Entity\ {
    Questao, 
    Questao_alternativa, 
    Estacao_melhoria_estacao, 
    Jogador
};
use App\Controllers\ {
    AuthController,
    TriboController,
    EstacaoMelhoriaController,
    EstacaoMelhoriaEstacaoController
};

use App\Middlewares\ {
    JwtDateTimeMiddleware
};

$app = new \Slim\App(slimConfiguration());

$container = $app->getContainer();

/**
* Criar log da api
*/
$container['logger'] = function($container) {
    $logger = new Monolog\Logger('OGS-microservice');
    $logfile = 'C:/Apache24/htdocs/SlimOGS/logs/OGS-microservice.log';
    //    $logfile = '/var/www/SlimOGS/logs/OGS-microservice.log';
    $stream = new Monolog\Handler\StreamHandler($logfile, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream, Monolog\Logger::INFO);
    $logger->pushHandler($fingersCrossed);
    return $logger;
};

/**
 * Ignora diferença entre url/teste e url/teste/
 */
$app->add(new TrailingSlash(false));

//$app->add(new JwtDateTimeMiddleware())->add(jwtAuth());

/**
 * HTTP Auth - Autenticação minimalista para retornar um JWT
 */
$app->post('/auth' , AuthController::class . ':login') ;

$app->post('/refresh-token' , AuthController::class . ':refreshToken')
        ->add(jwtAuth());

$app->get('/teste' , function(){echo getenv('JWT_SECRET_KEY');})
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

/**Routes GEY*/
$app->get('/questao/{id_questao}', function (Request $request, Response $response, array $args) {
  
    $id_questao = $args['id_questao'];
    $entityManager = $this->get('em');
    
    $repository = $entityManager->getRepository('App\Models\Entity\Questao');
    $questao = $repository->find($id_questao);
    
    $repositoryAlternativas = $entityManager->getRepository('App\Models\Entity\Questao_alternativa');
    $alternativas = $repositoryAlternativas->findBy(array('id_questao' => $id_questao));
    $questao->setAlternativas($alternativas);
    
    $response->getBody()->write(json_encode($questao,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/questao_alternativa/{id_questao_alternativa}', function (Request $request, Response $response, array $args) {
    $id_questao_alternativa = $args['id_questao_alternativa'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Questao_alternativa');
    $questao_alternativa = $repository->find($id_questao_alternativa);
    
    $response->getBody()->write(json_encode($questao_alternativa,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/aluno/{id_aluno}', function (Request $request, Response $response, array $args) {
    $id_aluno = $args['id_aluno'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Aluno');
    $aluno = $repository->find($id_aluno);
    
    $response->getBody()->write(json_encode($aluno,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/assunto/{id_assunto}', function (Request $request, Response $response, array $args) {
    $id_assunto = $args['id_assunto'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Assunto');
    $assunto = $repository->find($id_assunto);
    
    $response->getBody()->write(json_encode($assunto,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/frequencia/{id_frequecia}', function (Request $request, Response $response, array $args) {
    $id_frequecia = $args['id_frequecia'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Frequencia');
    $frequencia = $repository->find($id_frequecia);
    
    $response->getBody()->write(json_encode($frequencia,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/jogador/{id_jogador}', function (Request $request, Response $response, array $args) {
    $id_jogador = $args['id_jogador'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Jogador');
    $jogador = $repository->find($id_jogador);
    
    $response->getBody()->write(json_encode($jogador,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/tribo/{id_tribo}', TriboController::class . ':triboPorId')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/triboPorEmail/{email}', TriboController::class . ':triboPorUsuario')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaomelhoriaPorTipoNivel', EstacaoMelhoriaController::class . ':consultarEstacaomelhoriaPorTipoNivel')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaEstacaoPorEstacao', EstacaoMelhoriaEstacaoController::class . ':consultarEstacaomelhoriaEstacaoPorEstacao' )
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoTipo/{id_estacao_Tipo}', function (Request $request, Response $response, array $args) {
    $id_estacao_Tipo = $args['id_estacao_Tipo'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Estacao_tipo');
    $estacao_Tipo = $repository->find($id_estacao_Tipo);
    /**
     * Verifica se existe a tribo com a ID informada
     */
    if (!$estacao_Tipo) {
        $logger = $this->get('logger');
        $logger->warning("EstacaoTipo {$id_estacao_Tipo} Not Found");
        throw new \Exception("EstacaoTipo {$id_estacao_Tipo} not Found", 404);
    }       
    
    $response->getBody()->write(json_encode($estacao_Tipo,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

})
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/estacao_melhoria', EstacaoMelhoriaEstacaoController::class . ':inserirEstacaomelhoriaEstacao' )
->add(new JwtDateTimeMiddleware())->add(jwtAuth());


$app->run();

