<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Models\Entity\Jogador;

final class AuthController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    private function geraRefreshToken($nick_name) : string{
        $refreshTokenPayload = [
            'nick_name' => $nick_name,
            'rambom' => uniqid()
        ];
        return JWT::encode($refreshTokenPayload, $this->container['secretkey']);
    }
    
    private function geraToken($id_jogador, $validade) : string{ 
        $data_criacao = new \DateTime();
        $payload = [
            "sub" => $id_jogador,
            "iss" => "api.guardioesdosaber.com.br",
            "iat" => $data_criacao->format('Y-m-d H:i:s'),
            "expired_at" => $validade->format('Y-m-d H:i:s')
        ];
        
        //$token = JWT::encode($payload, $this->container['secretkey'], "HS512");
        return JWT::encode($payload, $this->container['secretkey']);
    }
    
    public function login(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        if(!empty($params->password) && !empty($params->user)){
            $Password = $params->password;
            $usuario = strtolower($params->user);
            $repository = $this->entityManager->getRepository('App\Models\Entity\Jogador');
            $jogador = $repository->findOneBy(array('nick_name' => $usuario));
            
            if(!is_null($jogador)){
                if (!$jogador->login($Password)) {
                    $this->logger->error("Login {$usuario} senha incorreta");
                    throw new \Exception("Senha incorreta", 412);
                    die;
                }
            }else{
                $this->logger->error("Login {$usuario} usuário não encontrado");
                throw new \Exception("Nenhum usuario informado não existe", 412);
                die;
            }
            
        }else{
            $this->logger->error("Login sem usuario ou senha");
            throw new \Exception("Informe usuario e senha", 412);
            die;
        }
        
        $validade = new \DateTime();
        date_time_set( $validade , 23 , 59 , 59 );
        $token = $this->geraToken($jogador->id_jogador, $validade);
        $refreshToken = $this->geraRefreshToken($jogador->nick_name);
        
        $tokenJogadorControler = new TokenJogadorController( $this->container);
        $tokenJogadorControler->inserirTokenJogador($token, $refreshToken, $validade,  $jogador->id_jogador);
        
        return $response->withJson(["apiToken" => $token, "apiRefreshToken" => $refreshToken], 200)
            ->withHeader('Content-type', 'application/json'); 
    }
    
    public function refreshToken(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        $refreshTokenDecoded = JWT::decode(
            $params->refreshToken,
            getenv('JWT_SECRET_KEY'),
            ['HS256']
        );
        
        $tokenJogadorControler = new TokenJogadorController($this->container);
        $token_jogador = $tokenJogadorControler->consultarPorRefreshToken($params->refreshToken);
        if(is_null($token_jogador)){
            return $response->withJson(json_encode(['message' => 'RefreshToken invalido'],  256))
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(401);
        }
        
        $validade = new \DateTime();
        date_time_set( $validade , 23 , 59 , 59 );
        $token = $this->geraToken($token_jogador->id_jogador, $validade);
        $refreshToken = $this->geraRefreshToken($refreshTokenDecoded->nick_name);
        $tokenJogadorControler->inserirTokenJogador($token, $refreshToken, $validade,  $token_jogador->id_jogador);
        
        return $response->withJson(["apiToken" => $token, "apiRefreshToken" => $refreshToken], 200)
            ->withHeader('Content-type', 'application/json');
    }
}