<?php
use Psr7Middlewares\Middleware\TrailingSlash;
use App\Middlewares\JwtDateTimeMiddleware;
use function src\{
    slimConfiguration,
    jwtAuth
};
use App\Controllers\ {
    AuthController,
    AssuntoController,
    DesafioController,
    DesafioJogadorController,
    EstacaoMelhoriaController,
    EstacaoMelhoriaEstacaoController,
    EstacaoTipoController,
    FrequenciaController,
    JogadorController,
    QuestaoAlternativaController,
    QuestaoController,
    QuestaoDesafioJogadorController,
    TriboController
};

$app = new \Slim\App(slimConfiguration());

/**
 * Ignora diferença entre url/teste e url/teste/
 */
$app->add(new TrailingSlash(false));
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

$app->get('/teste' , function(){

});

$app->get('/aluno/{id_aluno}', AlunoController::class . ':consultarAlunoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/assunto/{id_assunto}', AssuntoController::class . ':consultarAssuntoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/desafios/{id_jogador}', DesafioController::class . ':consultarDesafios')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaEmConstrucao', EstacaoMelhoriaEstacaoController::class . ':consultarEstacaoMelhoriaEmConstrucao' )
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaEstacaoPorEstacao', EstacaoMelhoriaEstacaoController::class . ':consultarEstacaomelhoriaEstacaoPorEstacao' )
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoMelhoriaPorSabedoria', EstacaoMelhoriaController::class . ':consultarEstacaomelhoriaPorSabedoria')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/estacaoTipo/{id_estacao_Tipo}', EstacaoTipoController::class . ':consultarEstacaoTipoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/frequencia/{id_frequecia}', FrequenciaController::class . ':consultarFrequenciaPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/jogador/{id_jogador}', JogadorController::class . ':consultarJogadorPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/questao/{id_questao}', QuestaoController::class . ':consultarQuestaoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/questaoAlternativa/{id_questao_alternativa}', QuestaoAlternativaController::class . ':consultarQuestaoAlternativaPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/questoesNovoDesafio', QuestaoController::class . ':consultarQuestaoParaDesafio')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/tribo/{id_tribo}', TriboController::class . ':consultarTriboPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->get('/triboPorUsuario/{nick_name}', TriboController::class . ':consultarTriboPorUsuario')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/auth' , AuthController::class . ':login') ;

$app->post('/desafioJogador', DesafioJogadorController::class . ':inserirDesafioJogador')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/estacaoMelhoria', EstacaoMelhoriaEstacaoController::class . ':inserirEstacaomelhoriaEstacao' )
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/estacaoMelhoriaEstacaoConstrucao', EstacaoMelhoriaEstacaoController::class . ':atualizarEstacaoMelhoriaEstacaoConstrucao' );

$app->post('/questaoDesafioJogador', QuestaoDesafioJogadorController::class . ':inserirQuestaoDesafioJogador')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->post('/refreshToken' , AuthController::class . ':refreshToken')
    ->add(jwtAuth());

$app->run();