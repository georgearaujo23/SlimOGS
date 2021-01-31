<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="desafio_jogador")
 */
class Desafio_jogador{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_desafio_jogador;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_jogador;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_desafio;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $quantidade_respondida;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $quantidade_acertos;
    
    /**
     * @var boolean
     * @Column(type="boolean")
     */
    public $terminou;
}
