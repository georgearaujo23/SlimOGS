<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Questao;
use App\Models\Entity\Questao_alternativa;
use Firebase\JWT\JWT;

require '../config.php';

/*Create Routes*/
$app->get('/questao/{id_questao}', function (Request $request, Response $response, array $args) {
    /*$stmt = $this->db_Connect->query("SELECT * FROM questao where id_questao = {$args['id_questao']}");
    $questao = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt2 = $this->db_Connect->query("SELECT id_questao_alternativa, texto, id_questao, CASE correta WHEN 0 THEN 'false' ELSE 'true' END as correta FROM ogs.questao_alternativa WHERE id_questao = {$args['id_questao']}");
    $questaoa = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    $questao[0]['alternativas'] = $questaoa;
    $response->getBody()->write(json_encode($questao[0],  256));
    //return $response->withHeader('Content-type', 'application/json')
     //       ->withStatus(200);
    //echo json_encode($questao[0],  256);*/
    
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

/**
 * HTTP Auth - Autenticação minimalista para retornar um JWT
 */
$app->get('/auth', function (Request $request, Response $response) use ($app) {

     $key = $this->get("secretkey");

    $token = array(
        "user" => "@fidelissauro",
        "twitter" => "https://twitter.com/fidelissauro",
        "github" => "https://github.com/msfidelis"
    );

    $jwt = JWT::encode($token, $key);

    return $response->withJson(["auth-jwt" => $jwt], 200)
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

