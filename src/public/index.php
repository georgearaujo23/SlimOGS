<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Questao;
use App\Models\Entity\Questao_alternativa;
use Firebase\JWT\JWT;

require '../config.php';

/*Routes GEY*/
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

});

$app->get('/questao_alternativa/{id_questao_alternativa}', function (Request $request, Response $response, array $args) {
    $id_questao_alternativa = $args['id_questao_alternativa'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Questao_alternativa');
    $questao_alternativa = $repository->find($id_questao_alternativa);
    
    $response->getBody()->write(json_encode($questao_alternativa,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/aluno/{id_aluno}', function (Request $request, Response $response, array $args) {
    $id_aluno = $args['id_aluno'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Aluno');
    $aluno = $repository->find($id_aluno);
    
    $response->getBody()->write(json_encode($aluno,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/assunto/{id_assunto}', function (Request $request, Response $response, array $args) {
    $id_assunto = $args['id_assunto'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Assunto');
    $assunto = $repository->find($id_assunto);
    
    $response->getBody()->write(json_encode($assunto,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/frequencia/{id_frequecia}', function (Request $request, Response $response, array $args) {
    $id_frequecia = $args['id_frequecia'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Frequencia');
    $frequencia = $repository->find($id_frequecia);
    
    $response->getBody()->write(json_encode($frequencia,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/jogador/{id_jogador}', function (Request $request, Response $response, array $args) {
    $id_jogador = $args['id_jogador'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Jogador');
    $jogador = $repository->find($id_jogador);
    
    $response->getBody()->write(json_encode($jogador,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/tribo/{id_tribo}', function (Request $request, Response $response, array $args) {
    $id_tribo = $args['id_tribo'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Tribo');
    $tribo = $repository->find($id_tribo);
    /**
     * Verifica se existe a tribo com a ID informada
     */
    if (!$tribo) {
        $logger = $this->get('logger');
        $logger->warning("Tribo {$id_tribo} Not Found");
        throw new \Exception("Tribo not Found", 404);
    }       
    
    $response->getBody()->write(json_encode($tribo,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

/**
 * HTTP Auth - Autenticação minimalista para retornar um JWT
 */
$app->get('/auth', function (Request $request, Response $response) use ($app) {
    
    $now = new DateTime( $request->getQueryParams()['DATA_ACESSO']);
    $future = date_add($now, new DateInterval("PT15H50M0S"));
    $server = $request->getServerParams();
    
    $payload = [
        "usuarioSenha" => $request->getUri()->getUserInfo(),
        "iat" => $now->getTimeStamp(),
        "sub" => $server["PHP_AUTH_USER"]
    ];
    $token = JWT::encode($payload, $this->get("secretkey"), "HS512");
    
    return $response->withJson(["API_CHAVE" => $token], 200)
        ->withHeader('Content-type', 'application/json'); 
    
});

/*
/*Working with POST Data
$app->post('/ticket/new', function (Request $request, Response $response) {
    $data = $request->getParsedBody();
    $ticket_data = [];
    $ticket_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
    $ticket_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
    // ...
*/

$app->run();

