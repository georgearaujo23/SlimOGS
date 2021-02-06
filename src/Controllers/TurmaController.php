<?php

namespace App\Controllers;

use http\Exception\RuntimeException;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\Models\Entity\ {
    Turma
};

final class TurmaController {
    
    protected $container;
    protected $entityManager;
    protected $logger;

   // constructor receives container instance
   public function __construct(\Slim\Container $container) {
       $this->container = $container;
       $this->entityManager = $container['em'];
       $this->logger = $container['logger'];
   }

    public function listarPorProfessor(Request $request, Response $response, array $args) : Response {
        $id_professor = $args['id_professor'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Turma');
        $turmas = $repository->findBy(array('id_professor' => $id_professor));
        if (!$turmas) {
            $this->logger->warning("Turma Not Found");
            throw new \Exception("Turma not Found", 404);
        }
        $response->getBody()->write(json_encode($turmas,  256));
        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function obterTurma(Request $request, Response $response, array $args) : Response {
        $id_turma = $args['id_turma'];
        $repository = $this->entityManager->getRepository('App\Models\Entity\Turma');
        $turma = $repository->find($id_turma);

        if (!$turma) {
            $this->logger->warning("Turma {$id_turma} Not Found");
            throw new \Exception("Turma not Found", 404);
        }

        $response->getBody()->write(json_encode($turma,  256));

        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

    public function listarAlunos(Request $request, Response $response, array $args) : Response {
        $id_turma = $args['id_turma'];

        $query = $this->entityManager->createQuery('SELECT ta, a FROM App\Models\Entity\Turma_aluno ta '
            . 'JOIN ta.aluno a '
            . 'WHERE ta.id_turma = ?1');
        $query->setParameter(1, $id_turma);
        $response->getBody()->write(json_encode( $query->getResult(),  256));

        return $response->withHeader('Content-type', 'application/json')
            ->withStatus(200);
    }

}