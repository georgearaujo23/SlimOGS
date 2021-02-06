<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="turma")
 */
class Turma{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_turma;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $nome;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_professor;
    
}

