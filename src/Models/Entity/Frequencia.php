<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="frequencia")
 */
class Frequencia{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_frequencia;
    
    /**
     * @var DateTime
     * @Column(type="date")
     */
    public $data_aula;
    
    /**
     * @var boolean
     * @Column(type="boolean")
     */
    public $presente;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $bonus_participacao;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_turma_aluno;
    
}

