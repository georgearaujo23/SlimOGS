<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;
use App\Models\Entity\Jogador;

final class RankingController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function ConsultarRankingNivel(Request $request, Response $response) : Response{
        $params = (object) $request->getQueryParams();   
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        ORDER BY u.nivel DESC
        ')->setMaxResults(10);
        
        $top10 = $query->getResult();
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        WHERE e.nick_name = :nick_name
        ')->setParameter('nick_name', strtolower($params->nick_name));
        $user = $query->getResult()[0];
        $union["{$user['nick_name']}"] = $user;
        foreach($top10 as $val) {
            $user = $val;
            $union["{$user['nick_name']}"] = $user;
        }
        $rankingNivel = array();
        foreach($union as $val) {
            array_push($rankingNivel, $val);
        }
        $response->getBody()->write('{ "ranking": ' . json_encode($rankingNivel,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function ConsultarRankingSustentavel(Request $request, Response $response) : Response{
        $params = (object) $request->getQueryParams();   
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.nivel_sustentavel as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        ORDER BY u.nivel_sustentavel DESC
        ')->setMaxResults(10);
        
        $top10 = $query->getResult();
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.nivel_sustentavel as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        WHERE e.nick_name = :nick_name
        ')->setParameter('nick_name', strtolower($params->nick_name));
        $user = $query->getResult()[0];
        $union["{$user['nick_name']}"] = $user;
        foreach($top10 as $val) {
            $user = $val;
            $union["{$user['nick_name']}"] = $user;
        }
        $rankingNivel = array();
        foreach($union as $val) {
            array_push($rankingNivel, $val);
        }
        $response->getBody()->write('{ "ranking": ' . json_encode($rankingNivel,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function ConsultarRankingSabedoria(Request $request, Response $response) : Response{
        $params = (object) $request->getQueryParams();   
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.sabedoria as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        ORDER BY u.sabedoria DESC
        ')->setMaxResults(10);
        
        $top10 = $query->getResult();
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.sabedoria as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        WHERE e.nick_name = :nick_name
        ')->setParameter('nick_name', strtolower($params->nick_name));
        $user = $query->getResult()[0];
        $union["{$user['nick_name']}"] = $user;
        foreach($top10 as $val) {
            $user = $val;
            $union["{$user['nick_name']}"] = $user;
        }
        $rankingNivel = array();
        foreach($union as $val) {
            array_push($rankingNivel, $val);
        }
        $response->getBody()->write('{ "ranking": ' . json_encode($rankingNivel,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
    public function ConsultarRankingReputacao(Request $request, Response $response) : Response{
        $params = (object) $request->getQueryParams();   
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.reputacao as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        ORDER BY u.reputacao DESC
        ')->setMaxResults(10);
        
        $top10 = $query->getResult();
        
        $query = $this->entityManager->createQuery('
        SELECT e.nick_name, u.reputacao as nivel FROM App\Models\Entity\Tribo u
        INNER JOIN u.jogador e
        WHERE e.nick_name = :nick_name
        ')->setParameter('nick_name', strtolower($params->nick_name));
        $user = $query->getResult()[0];
        $union["{$user['nick_name']}"] = $user;
        foreach($top10 as $val) {
            $user = $val;
            $union["{$user['nick_name']}"] = $user;
        }
        $rankingNivel = array();
        foreach($union as $val) {
            array_push($rankingNivel, $val);
        }
        $response->getBody()->write('{ "ranking": ' . json_encode($rankingNivel,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }
    
}