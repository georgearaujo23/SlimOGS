<?php
use Psr7Middlewares\Middleware\TrailingSlash;
use App\Middlewares\JwtDateTimeMiddleware;
use function src\{
    slimConfiguration,
    jwtAuth
};
use App\Controllers\ {
    AuthController, AuthProfessorController, AssuntoController, DesafioController, 
    DesafioJogadorController,EstacaoController, EstacaoMelhoriaController, 
    EstacaoMelhoriaEstacaoController, EstacaoTipoController,
    FrequenciaController, JogadorController, QuestaoAlternativaController, QuestaoController,
    QuestaoDesafioJogadorController, TriboController, TurmaAlunoController, TurmaController,
    RankingController, VersaoApkController, BonificacaoController
};

$app = new \Slim\App(slimConfiguration());

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, x-token')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

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
    $logfile = $_SERVER['DOCUMENT_ROOT'] . '/logs/OGS-microservice.log';
    $stream = new Monolog\Handler\StreamHandler($logfile, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream, Monolog\Logger::INFO);
    $logger->pushHandler($fingersCrossed);
    return $logger;
};

$app->get('/teste' , function(){
    echo uff8_encode("API Guardiões do Saber");
});

/**Routes GET*/
$app->get('/aluno/{id_aluno}', AlunoController::class . ':consultarAlunoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/assunto/{id_assunto}', AssuntoController::class . ':consultarAssuntoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/atualizarMoedas', TriboController::class . ':atualizarMoedas');
$app->get('/atualizarRecursos', EstacaoController::class . ':atualizarRecursos');
$app->get('/bonificacao', BonificacaoController::class . ':consultarExistencia')
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
$app->get('/frequencia/{id_frequencia}', FrequenciaController::class . ':obterFrequenciaPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/frequencia/listarPorTurmaAluno/{id_turma_aluno}', FrequenciaController::class . ':listarPorTurmaAluno')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/frequencia/listarPorTurma/{id_turma}', FrequenciaController::class . ':listarPorTurma')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/jogador/{id_jogador}', JogadorController::class . ':consultarJogadorPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/questao/{id_questao}', QuestaoController::class . ':consultarQuestaoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/questaoAlternativa/{id_questao_alternativa}', QuestaoAlternativaController::class . ':consultarQuestaoAlternativaPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/questoesNovoDesafio', QuestaoController::class . ':consultarQuestaoParaDesafio')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/questoes/list', QuestaoController::class . ':listarQuestoes');
    #->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/rankingNivel', RankingController::class . ':ConsultarRankingNivel')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/rankingReputacao', RankingController::class . ':ConsultarRankingReputacao')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());    
$app->get('/rankingSustentavel', RankingController::class . ':ConsultarRankingSustentavel')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());    
$app->get('/rankingSabedoria', RankingController::class . ':ConsultarRankingSabedoria')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/tribo/{id_tribo}', TriboController::class . ':consultarTriboPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/triboPorUsuario/{nick_name}', TriboController::class . ':consultarTriboPorUsuario')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/turmaAluno/{id_turma_aluno}', TurmaAlunoController::class . ':consultarTurmaAlunoPorId')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/turma/listarPorProfessor/{id_professor}', TurmaController::class . ':listarPorProfessor')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/turma/{id_turma}', TurmaController::class . ':obterTurma')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/turma/listarAlunos/{id_turma}', TurmaController::class . ':listarAlunos')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/turma_aluno/{id_turma_aluno}', TurmaAlunoController::class . ':obterTurmaAluno')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->get('/versaoAtual', VersaoApkController::class . ':consultarVersaoAtual');

/**Routes POST*/
$app->post('/auth' , AuthController::class . ':login');
$app->post('/auth-app-professor' , AuthProfessorController::class . ':login');
$app->post('/desafioJogador', DesafioJogadorController::class . ':inserirDesafioJogador')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->post('/estacaoMelhoria', EstacaoMelhoriaEstacaoController::class . ':inserirEstacaomelhoriaEstacao' )
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->post('/estacaoMelhoriaEstacaoConstrucao', EstacaoMelhoriaEstacaoController::class . ':atualizarEstacaoMelhoriaEstacaoConstrucao' )
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->post('/questaoDesafioJogador', QuestaoDesafioJogadorController::class . ':inserirQuestaoDesafioJogador')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->post('/receberBonificao' , BonificacaoController::class . ':receberBonificao')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());
$app->post('/refreshToken' , AuthController::class . ':refreshToken')
    ->add(jwtAuth());
$app->post('/turma_aluno/{id_turma_aluno}/frequencia/add', FrequenciaController::class . ':salvarFrequencia')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

/**Routes PUT*/
$app->put('/turma_aluno/{id_turma_aluno}/frequencia/{id_frequencia}', FrequenciaController::class . ':editarFrequencia')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

/**Routes DELETE*/
$app->delete('/turma_aluno/{id_turma}/frequencia/{data_aula}', FrequenciaController::class . ':excluirFrequencia')
    ->add(new JwtDateTimeMiddleware())->add(jwtAuth());

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();