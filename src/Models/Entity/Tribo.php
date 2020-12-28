<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="tribo")
 **/
class Tribo{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    public $id_tribo;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $producao_agua;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $producao_comida;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $producao_energia;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $consumo_agua;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $consumo_comida;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $consumo_energia;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $reputacao;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $nivel;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $nivel_sustentavel;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $nivel_sabedoria;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $id_jogador;
   
}


