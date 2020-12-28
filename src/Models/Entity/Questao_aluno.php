<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="questao_aluno")
 */
class Questao_aluno{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_questao_aluno;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_aluno;
    
    /**
     * @One
     */
    public $aluno;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_questao;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_questao_alternativa;
    
}
