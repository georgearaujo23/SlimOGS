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
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $experiencia;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $experiencia_prox;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $moedas;
    
    /** 
    *  
    * @OneToMany(targetEntity="Estacao", mappedBy="Tribo", cascade={"persist", "remove"}) 
    */
    public $estacoes;

    public function __construct(){
        $this->estacoes = new ArrayCollection();
    }
   
}


