<?php
use Psr7Middlewares\Middleware\TrailingSlash;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use function src\{
    slimConfiguration,
    jwtAuth
};
use App\Models\Entity\ {
    Questao_alternativa
};
use App\Controllers\ {
    AuthController,
    TriboController,
    EstacaoMelhoriaController,
    EstacaoMelhoriaEstacaoController,
    JogadorController,
    DesafioController,
    QuestaoController,
    DesafioJogadorController
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
    //local
    $logfile = 'C:/Apache24/htdocs/SlimOGS/logs/OGS-microservice.log';
    //IF
    //$logfile = '/var/www/SlimOGS/logs/OGS-microservice.log';
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

$app->get('/teste' , function(){
    $dateInLocal = date("Y-m-d H:i:s");
    var_dump($time);
    var_dump(date("Y-m-d H:i:s"));
});

/**Routes GEY*/
$app->get('/questao/{id_questao}', QuestaoController::class . ':consultarQuestaoPorId')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/questoesNovoDesafio', QuestaoController::class . ':consultarQuestaoParaDesafio')
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

$app->get('/jogador/{id_jogador}', JogadorController::class . ':consultarJogadorPorId')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/desafios/{id_jogador}', DesafioController::class . ':consultarDesafios')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/tribo/{id_tribo}', TriboController::class . ':triboPorId')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/triboPorUsuario/{usuario}', TriboController::class . ':triboPorUsuario')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaomelhoriaPorSabedoria', EstacaoMelhoriaController::class . ':consultarEstacaomelhoriaPorSabedoria')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaEstacaoPorEstacao', EstacaoMelhoriaEstacaoController::class . ':consultarEstacaomelhoriaEstacaoPorEstacao' )
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaEmConstrucao', EstacaoMelhoriaEstacaoController::class . ':consultarEstacaoMelhoriaEmConstrucao' )
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

$app->post('/estacaoMelhoriaEstacaoConstrucao', EstacaoMelhoriaEstacaoController::class . ':atualizarEstacaoMelhoriaEstacaoConstrucao' )
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/desafioJogador', DesafioJogadorController::class . ':incluirDesafioJogador')
->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->run();

