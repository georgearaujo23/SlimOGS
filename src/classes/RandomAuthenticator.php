<?php
require '../vendor/autoload.php';
use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface;


class RandomAuthenticator implements AuthenticatorInterface {
    private $entityManager; 
    private $logger;
    public function __construct($entityManager, $logger) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }
    public function __invoke(array $arguments): bool {
        
        if(!empty($arguments['password']) && !empty($arguments['user'])){
             
            $Password = $arguments['password'];
            $email = $arguments['user'];
            $repository = $this->entityManager->getRepository('App\Models\Entity\Jogador');
            $jogador = $repository->findOneBy(array('email' => $email));
            
            if(!is_null($jogador)){
                if ($jogador->login($Password)) {
                    return true;
                }else{
                    $this->logger->error("Login {$email} senha incorreta");
                    throw new \Exception("Senha incorreta", 412);
                    die;
                }
            }else{
                $this->logger->error("Login {$email} email não encontrado");
                throw new \Exception("Nenhum usuario encontrado para o email informado", 412);
                die;
            }
            
        }else{
            $this->logger->error("Login sem email ou senha");
            throw new \Exception("Informe email e senha", 412);
            die;
        }
        return false;
    }
}