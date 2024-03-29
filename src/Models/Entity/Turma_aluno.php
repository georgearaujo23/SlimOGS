<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="turma_aluno")
 */
class Turma_aluno{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_turma_aluno;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $presencas_consecutivas;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_turma;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_aluno;
    
    /** 
     * @OneToMany(targetEntity="Frequencia", mappedBy="Turma_aluno", cascade={"persist", "remove"}) 
     */
    public $frequencias;
    
    /**
     * @OneToOne(targetEntity="Aluno", mappedBy="Turma_aluno", cascade={"persist", "remove"})
     * @JoinColumn(name="id_aluno", referencedColumnName="id_aluno")
     */
    public $aluno;
    
    /**
     * @OneToOne(targetEntity="Turma", mappedBy="Turma_aluno", cascade={"persist", "remove"})
     * @JoinColumn(name="id_turma", referencedColumnName="id_turma")
     */
    public $turma;

}

