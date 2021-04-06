<?php
namespace App\Controllers;

use http\Exception\RuntimeException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\{Frequencia, Turma};

final class FrequenciaController {
    protected $container;
    protected $entityManager;
    protected $logger;

    // constructor receives container instance
    public function __construct(\Slim\Container $container) {
        $this->container = $container;
        $this->entityManager = $container['em'];
        $this->logger = $container['logger'];
    }
    
    public function consultarFrequenciaPorId(Request $request, Response $response, array $args) : Response{
        $id_frequecia = $args['id_frequecia'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencia = $repository->find($id_frequecia);

        $response->getBody()->write(json_encode($frequencia,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);
    }

    public function listarPorTurmaAluno(Request $request, Response $response, array $args) : Response {
        $id_turma_aluno = $args['id_turma_aluno'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencias = $repository->findBy(array('id_turma_aluno' => $id_turma_aluno));
        $response->getBody()->write(json_encode($frequencias,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function listarPorTurma(Request $request, Response $response, array $args) : Response {
        $id_turma = $args['id_turma'];
        
        $query = $this->entityManager->createQuery('SELECT ta.id_turma, u.data_aula FROM App\Models\Entity\Frequencia u '
                . 'JOIN u.turma_aluno ta '
                . 'WHERE ta.id_turma = ?1 '
                . 'GROUP BY ta.id_turma, u.data_aula '
                . 'ORDER BY u.data_aula desc');
        $query->setParameter(1, $id_turma);
        $frequencias = $query->getResult();
        
        $response->getBody()->write(json_encode($frequencias,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }
    
    public function obterFrequenciaPorId(Request $request, Response $response, array $args) : Response{
        $id_frequencia = $args['id_frequencia'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencia = $repository->find($id_frequencia);

        if (!$frequencia) {
            $this->logger->warning("Frequencia {$id_frequencia} Not Found");
            throw new \Exception("Frequencia not Found", 404);
        }

        $response->getBody()->write(json_encode($frequencia,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);

    }

    public function salvarFrequencia(Request $request, Response $response, array $args) : Response{
        $id_turma_aluno = $args['id_turma_aluno'];

        $params = (object) $request->getParsedBody();
        $data_aula = new \DateTime($params->data_aula." 00:00:00");

        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencia = $repository->findOneBy(
            array('id_turma_aluno' => $id_turma_aluno, 'data_aula' => $data_aula)
        );

        if (!$frequencia) {
            $frequencia = new Frequencia();
            $frequencia->id_turma_aluno = $id_turma_aluno;
            $frequencia->data_aula = $data_aula;
            $frequencia->presente = $params->presente;
            $frequencia->bonus_participacao = $params->bonus_participacao;
            $this->entityManager->persist($frequencia);
            $this->entityManager->flush();
        }

        $frequencia = $repository->findOneBy(
            array('id_turma_aluno' => $id_turma_aluno, 'data_aula' => $data_aula)
        );

        $response->getBody()->write(json_encode($frequencia,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function editarFrequencia(Request $request, Response $response, array $args) : Response{
        $id_frequencia = $args['id_frequencia'];

        $params = (object) $request->getParsedBody();

        $repository = $this->entityManager->getRepository('App\Models\Entity\Frequencia');
        $frequencia = $repository->findOneBy(
            array('id_frequencia' => $id_frequencia)
        );

        if ($frequencia) {
            $frequencia->presente = $params->presente;
            $frequencia->bonus_participacao = $params->bonus_participacao;
            $this->entityManager->persist($frequencia);
            $this->entityManager->flush();
        }

        $frequencia = $repository->findOneBy(
            array('id_frequencia' => $id_frequencia)
        );

        $response->getBody()->write(json_encode($frequencia,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function excluirFrequencia(Request $request, Response $response, array $args) : Response{
        $id_turma = $args['id_turma'];
        $data_aula = new \DateTime($args['data_aula']." 00:00:00");

        $query = $this->entityManager->createQuery('SELECT u FROM App\Models\Entity\Frequencia u '
                . 'JOIN u.turma_aluno ta '
                . 'WHERE ta.id_turma = ?1 '
                . 'AND u.data_aula = ?2');
        $query->setParameter(1, $id_turma);
        $query->setParameter(2, $data_aula);
        $frequencias = $query->getResult();

        foreach ($frequencias as $frequencia) {
            $this->entityManager->remove($frequencia);
            $this->entityManager->flush();
        }

        $response->getBody()->write(json_encode($frequencias,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

}