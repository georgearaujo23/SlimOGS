<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="estacao")
 **/
class Estacao{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    public $id_estacao;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $producao;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $consumo;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $nivel;
    
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
    public $id_tribo;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $id_estacao_tipo;
    
    
    public $estacao_tipo;
    
    public function __construct(){
        $this->estacao_tipo = new Estacao_tipo();
    }
}


