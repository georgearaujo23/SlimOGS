<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="desafio")
 */
class Desafio{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_desafio;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $moedas;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $sabedoria;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $quantidade_questoes;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $quantidade_acertos;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $xp;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $descricao;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $data_inicio;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $data_fim;
    
}
