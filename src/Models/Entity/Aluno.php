<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="aluno")
 **/
class Aluno{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    public $id_aluno;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $matricula;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $nome;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_jogador;
    
    /**
     * @OneToOne(targetEntity="Jogador", mappedBy="Aluno", cascade={"persist", "remove"}) 
     * @JoinColumn(name="id_jogador", referencedColumnName="id_jogador")
     */
    public $jogador;
    
     /**
     * @OneToMany(targetEntity="Turma_aluno", mappedBy="Aluno", cascade={"persist", "remove"}) 
     */
    public $turmas;
    
    /**
     * @OneToMany(targetEntity="Questao_aluno", mappedBy="Aluno", cascade={"persist", "remove"}) 
     */
    public $questoesRespondidas;
    
    
}

