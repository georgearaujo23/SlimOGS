<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Models\Entity\Jogador;

final class AuthProfessorController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    private function geraRefreshToken($email) : string{
        $refreshTokenPayload = [
            'email' => $email,
            'rambom' => uniqid()
        ];
        return JWT::encode($refreshTokenPayload, $this->container['secretkey']);
    }
    
    private function geraToken($id_professor, $validade) : string{ 
        $data_criacao = new \DateTime();
        $payload = [
            "sub" => $id_professor,
            "iss" => "api.guardioesdosaber.com.br",
            "iat" => $data_criacao->format('Y-m-d H:i:s'),
            "expired_at" => $validade->format('Y-m-d H:i:s')
        ];
        
        //$token = JWT::encode($payload, $this->container['secretkey'], "HS512");
        return JWT::encode($payload, $this->container['secretkey']);
    }
    
    public function login(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        
        if(!empty($params->senha) && !empty($params->email)){
            $senha = $params->senha;
            $email = strtolower($params->email);
            $repository = $this->entityManager->getRepository('App\Models\Entity\Professor');
            $professor = $repository->findOneBy(array('email' => $email));
            
            if(!$professor){
                $this->logger->error("Login {$email} professor não encontrado");
                throw new \Exception('Professor informado nao existe', 412);
                die;
            }else{
                if (!$professor->login($senha)) {
                    $this->logger->error("Login {$email} senha incorreta");
                    throw new \Exception("Senha incorreta", 412);
                    die;
                }
            }
            
        }else{
            $this->logger->error("Login sem email ou senha");
            throw new \Exception("Informe email e senha", 412);
            die;
        }
        
        $validade = new \DateTime();
        date_time_set( $validade , 23 , 59 , 59 );
        $token = $this->geraToken($professor->id_professor, $validade);
        $refreshToken = $this->geraRefreshToken($professor->email);
        
        $tokenProfessorControler = new TokenProfessorController( $this->container);
        $tokenProfessorControler->inserirTokenProfessor($token, $refreshToken, $validade,  $professor->id_professor);
        
        return $response->withJson(["apiToken" => $token, "apiRefreshToken" => $refreshToken, "id_professor" => $professor->id_professor], 200)
            ->withHeader('Content-type', 'application/json'); 
    }
    
    public function refreshToken(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        $refreshTokenDecoded = JWT::decode(
            $params->refreshToken,
            getenv('JWT_SECRET_KEY'),
            ['HS256']
        );
        
        $tokenProfessorControler = new TokenProfessorController($this->container);
        $token_professor = $tokenProfessorControler->consultarPorRefreshToken($params->refreshToken);
        if(is_null($token_professor)){
            return $response->withJson(json_encode(['message' => 'RefreshToken invalido'],  256))
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(401);
        }
        
        $validade = new \DateTime();
        date_time_set( $validade , 23 , 59 , 59 );
        $token = $this->geraToken($token_professor->id_professor, $validade);
        $refreshToken = $this->geraRefreshToken($refreshTokenDecoded->email);
        $tokenProfessorControler->inserirTokenProfessor($token, $refreshToken, $validade,  $token_professor->id_professor);
        
        return $response->withJson(["apiToken" => $token, "apiRefreshToken" => $refreshToken, "id_professor" => $token_professor->id_professor], 200)
            ->withHeader('Content-type', 'application/json');
    }
}