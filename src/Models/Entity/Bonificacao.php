<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="bonificacao")
 */
class Bonificacao{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_bonificacao;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_tribo;
    
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
    public $xp;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $descricao;
    
    /**
     * @var boolean
     * @Column(type="boolean")
     */
    public $recebida;
    
}
