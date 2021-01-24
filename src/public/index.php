<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\Questao;
use App\Models\Entity\Questao_alternativa;
use App\Models\Entity\Estacao_melhoria_estacao;
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

$app->get('/triboPorEmail/{email}', function (Request $request, Response $response, array $args) {
    $email = $args['email'];
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Jogador');
    $jogador = $repository->findOneBy(array('email' => $email));
    $repository = $entityManager->getRepository('App\Models\Entity\Tribo');
    $tribo = $repository->findOneBy(array('id_jogador' => $jogador->id_jogador));
    /**
     * Verifica se existe a tribo com a ID informada
     */
    if (!$tribo) {
        $logger = $this->get('logger');
        $logger->warning("Tribo {$id_tribo} Not Found");
        throw new \Exception("Tribo not Found {$jogador->id_jogador}", 404);
    }       
    
    $repository = $entityManager->getRepository('App\Models\Entity\Estacao');
    $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));
    
    $response->getBody()->write(json_encode($tribo,  256));
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/estacaomelhoriaPorTipoNivel', function (Request $request, Response $response) {
    $params = (object) $request->getQueryParams();
    $id_estacao_tipo = $params->id_estacao_tipo;
    $nivel = $params->nivel;
     
    $entityManager = $this->get('em');
    $query = $entityManager->createQuery('SELECT u FROM App\Models\Entity\Estacao_melhoria u '
            . 'WHERE u.id_estacao_tipo = ?1'
            . 'AND u.nivel <= ?2');
    $query->setParameter(1, $id_estacao_tipo);
    $query->setParameter(2, $nivel);
    $estacao_tipo = $query->getResult(); 
    
    if (!$estacao_tipo) {
        $logger = $this->get('logger');
        $logger->warning("EstaçãoMelhoria not Found para tipo estação {$id_estacao_tipo}");
        $response->getBody()->write('{ "melhorias": [] }');
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    $response->getBody()->write('{ "melhorias": ' . json_encode($estacao_tipo,  256) ."}");
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

$app->get('/estacaoMelhoriaEstacaoPorEstacao', function (Request $request, Response $response) {
    $params = (object) $request->getQueryParams();
    $id_estacao = $params->id_estacao;
     
    $entityManager = $this->get('em');
    $query = $entityManager->createQuery('SELECT u, e FROM App\Models\Entity\Estacao_melhoria_estacao u '
            . 'JOIN u.estacao_melhoria e '
            . 'WHERE u.id_estacao = ?1');
    $query->setParameter(1, $id_estacao);
    $estacao_melhoria_estacao = $query->getResult(); 
    
    if (!$estacao_melhoria_estacao) {
        $logger = $this->get('logger');
        $logger->warning("EstaçãoMelhoriaEstacao not Found para id_estacao {$id_estacao}");
        $response->getBody()->write('{ "melhorias": [] }');
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    $response->getBody()->write('{ "melhorias": ' . json_encode($estacao_melhoria_estacao,  256) ."}");
    return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

});

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

$app->post('/estacao_melhoria', function (Request $request, Response $response) use ($app) {
    $params = (object) $request->getParsedBody();
    $entityManager = $this->get('em');
    $repository = $entityManager->getRepository('App\Models\Entity\Estacao_melhoria_estacao');
    $estacaoMelhoriaEstacao = $repository->findOneBy(
            array('id_estacao' => $params->id_estacao, 
                'id_estacao_melhoria' => $params->id_estacao_melhoria)
            );
    
    if (!$estacaoMelhoriaEstacao) {
        $estacaoMelhoriaEstacao = new Estacao_melhoria_estacao();
        $estacaoMelhoriaEstacao->quantidade = 1;
        $estacaoMelhoriaEstacao->id_estacao = $params->id_estacao;
        $estacaoMelhoriaEstacao->id_estacao_melhoria = $params->id_estacao_melhoria;
        $repository = $entityManager->getRepository('App\Models\Entity\Estacao_melhoria');
        $estacao_melhoria = $repository->find($params->id_estacao_melhoria);
        $estacaoMelhoriaEstacao->estacao_melhoria = $estacao_melhoria;
        /**
        * Persiste a entidade no banco de dados
        */
       $entityManager->persist($estacaoMelhoriaEstacao);
       $entityManager->flush();
    }else{
        $estacaoMelhoriaEstacao->quantidade += 1;
        $entityManager->flush();
    }

    $repository = $entityManager->getRepository('App\Models\Entity\Tribo');
    $tribo = $repository->find($params->id_tribo);
    
    $repository = $entityManager->getRepository('App\Models\Entity\Estacao');
    $tribo->estacoes = $repository->findBy(array('id_tribo' => $tribo->id_tribo));
    
    
    /**
     * Verifica se existe a tribo com a ID informada
     */
    if (!$tribo) {
        $logger = $this->get('logger');
        $logger->warning("Tribo {$params->id_tribo} Not Found");
        throw new \Exception("Tribo not Found", 404);
    }       
    $return = $response->withJson($tribo, 200)
        ->withHeader('Content-type', 'application/json');
    return $return;       
});

$app->run();

