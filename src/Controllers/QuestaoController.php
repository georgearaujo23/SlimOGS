<?php
namespace App\Controllers;
use Doctrine\ORM\Query\ResultSetMapping;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Questao;

final class QuestaoController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarQuestaoParaDesafio(Request $request, Response $response, array $args) : Response{
        $params = (object) $request->getQueryParams();    
        
        $repository = $this->entityManager->getRepository('App\Models\Entity\Desafio_jogador');
        $desafio_jogador = $repository->find($params->id_desafio_jogador);
        
        $query = $this->entityManager->createQuery('SELECT t.nivel FROM App\Models\Entity\Turma_aluno u '
                . 'JOIN u.turma t '
                . 'JOIN u.aluno a '
                . 'WHERE a.id_jogador = ?1 ');
        $query->setParameter(1, $desafio_jogador->id_jogador);
        $nivel = $query->getResult()[0]['nivel']; 
        
        $qb = $this->entityManager->createQueryBuilder()
                ->select('u')
                ->from('App\Models\Entity\Questao', 'u')
                ->where('NOT EXISTS ('
                        . 'SELECT 1 FROM App\Models\Entity\Questao_desafio_jogador d '
                        . 'where d.id_questao = u.id_questao '
                        . 'AND d.id_desafio_jogador = ?1)'
                        . 'AND u.nivel <= ?2'
                        )
                ->orderBy('rand()')
                ->setMaxResults($params->quantidade_questoes)
                ->setParameter(1, $params->id_desafio_jogador)
                ->setParameter(2, $nivel);
        $query = $qb->getQuery();
        $questoes = $query->getResult(); 

        if (!$questoes) {
            $this->logger->warning("Questao not Found para o id_desafio_jogador {$params->id_desafio_jogador}");
            $response->getBody()->write('{ "questoes": [] }');
            return $response->withHeader('Content-type', 'application/json')
                    ->withStatus(200);
        }
        
        $alternativasController = new QuestaoAlternativaController($this->container);
        foreach($questoes as $questao){
            $alternativas = $alternativasController->consultarAlternativasPorIdQuestao($questao->id_questao);
            $questao->setAlternativas($alternativas);
        }

        $response->getBody()->write('{ "questoes": ' . json_encode($questoes,  256) ."}");
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
    
    public function consultarQuestaoPorId(Request $request, Response $response, array $args) : Response{
        $id_questao = $args['id_questao'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Questao');
        $questao = $repository->find($id_questao);

        $alternativasController = new QuestaoAlternativaController($this->container);
        $alternativas = $alternativasController->consultarAlternativasPorIdQuestao($questao->id_questao);
        $questao->setAlternativas($alternativas);

        $response->getBody()->write(json_encode($questao,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }

    public function listarQuestoes(Request $request, Response $response, array $args) : Response{
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id_questao', 'id_questao');
        $rsm->addScalarResult( 'assunto', 'assunto');
        $rsm->addScalarResult('NIVEL', 'NIVEL');
        $rsm->addScalarResult('total_respostas', 'total_respostas');
        $rsm->addScalarResult('percentual_acerto', 'percentual_acerto');
        
        $query = $this->entityManager->createNativeQuery("SELECT
                    q.id_questao, 
                    a.descricao as assunto,
                    q.NIVEL,
                    COUNT(qd.id_questao) as total_respostas,
                    FLOOR(((SUM(CASE WHEN qa.id_questao_alternativa = qd.id_questao_alternativa THEN 1 ELSE 0 END) / COUNT(*))* 100) )  AS percentual_acerto
                    FROM questao q
                    LEFT JOIN questao_desafio_jogador qd ON qd.id_questao = q.id_questao
                    INNER JOIN assunto a ON a.id_assunto = q.id_assunto
                    INNER JOIN questao_alternativa qa ON qa.id_questao = q.id_questao AND qa.correta = 1
                    group by 
                    q.id_questao, 
                    a.descricao,
                    q.NIVEL
                    ORDER BY 3, 5 desc", $rsm);

        $questoes = $query->getResult();

        $response->getBody()->write(json_encode($questoes,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }
}