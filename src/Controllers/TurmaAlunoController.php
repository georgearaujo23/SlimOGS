<?php

namespace App\Controllers;

use http\Exception\RuntimeException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use Slim\Container;
use App\Models\Entity\ {
    Turma_aluno, Turma
};

final class TurmaAlunoController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
    public function __construct(Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }
    
   public function consultarTurmaAlunoPorId(Request $request, Response $response, array $args) : Response{
        $id_turma_aluno = $args['id_turma_aluno'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Turma_aluno');
        $turma_aluno = $repository->find($id_turma_aluno);

        if (!$turma_aluno) {
            $this->logger->warning("Turma Aluno {$id_turma_aluno} Not Found");
            throw new \Exception("Turma Aluno not Found", 404);
        }       

        $response->getBody()->write(json_encode($turma_aluno,  256));
        return $response->withHeader('Content-type', 'application/json')
                ->withStatus(200);

    }

    public function obterTurmaAluno(Request $request, Response $response, array $args) : Response {
        $id_turma_aluno = $args['id_turma_aluno'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Turma_aluno');
        $query = $this->entityManager->createQuery('SELECT t.id_turma, t.nome as turma, a.nome as aluno FROM App\Models\Entity\Turma_aluno ta '
            . 'JOIN ta.turma t '
            . 'JOIN ta.aluno a '
            . 'WHERE ta.id_turma_aluno = ?1');
        $query->setParameter(1, $id_turma_aluno);
        $response->getBody()->write(json_encode( $query->getResult()[0],  256));

        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }
    
}