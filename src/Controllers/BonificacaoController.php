<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\{
    Bonificacao, Bonificacao_diaria
};

final class BonificacaoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarExistencia(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();
        $id_tribo = $params->id_tribo;

        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Bonificacao u '
                . 'WHERE u.id_tribo = ?1'
                . 'AND u.recebida = 0');
        $query->setParameter(1, $id_tribo);
        $bonificacoes = $query->getResult(); 

        if (!$bonificacoes) {
            $this->logger->warning("Bonificações not Found para tribo estação {$id_tribo}");
            $response->getBody()->write('{ "bonificacoes": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }

        $response->getBody()->write('{ "bonificacoes": ' . json_encode($bonificacoes,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function receberBonificao(Request $request, Response $response) : Response{
        $params = (object) $request->getParsedBody();
        $repository = $this->entityManager->getRepository('App\Models\Entity\Bonificacao');
        $bonificacao = $repository->find($params->id_bonificacao);
        
        $bonificacao->recebida = true;
        $this->entityManager->flush();  
        
        $id_tribo = $params->id_tribo;

        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Bonificacao u '
                . 'WHERE u.id_tribo = ?1'
                . 'AND u.recebida = 0');
        $query->setParameter(1, $id_tribo);
        $bonificacoes = $query->getResult(); 

        if (!$bonificacoes) {
            $this->logger->warning("Bonificações not Found para tribo estação {$id_tribo}");
            $response->getBody()->write('{ "bonificacoes": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }

        $response->getBody()->write('{ "bonificacoes": ' . json_encode($bonificacoes,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
        
   }
 
    public function InserirBonificacaoPorAcesso($jogador){
        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Bonificacao_diaria u '
        . 'WHERE u.data_bonus = ?1'
        . 'AND u.id_jogador = ?2');
        $query->setParameter(1, date("Y-m-d"));
        $query->setParameter(2, $jogador->id_jogador);
        $bonusDiario = $query->getResult(); 
                
        if(!$bonusDiario){
            $repository = $this->entityManager->getRepository('App\Models\Entity\Tribo');
            $tribo = $repository->findOneBy(array('id_jogador' => $jogador->id_jogador));

            $bonificacao = new Bonificacao();
            $bonificacao->id_tribo = $tribo->id_tribo;
            $bonificacao->moedas = 50;
            $bonificacao->sabedoria = 100;
            $bonificacao->xp = 100;
            $bonificacao->descricao = utf8_encode('Bonificação de login diário');
            $bonificacao->recebida = false;
            $this->entityManager->persist($bonificacao);
            
            $bonusDiario = new Bonificacao_diaria();
            $bonusDiario->id_jogador = $jogador->id_jogador;
            $bonusDiario->data_bonus = date("Y-m-d");
            $this->entityManager->persist($bonusDiario);
            
            $this->entityManager->flush();  
        }
        
    }
   
}