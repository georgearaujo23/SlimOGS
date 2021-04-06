<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="bonificacao_diaria")
 */
class Bonificacao_diaria{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_bonificacao_diaria;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $data_bonus;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_jogador;
    
}
