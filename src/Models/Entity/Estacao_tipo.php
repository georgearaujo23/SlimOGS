<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="estacao_tipo")
 **/
class Estacao_tipo{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    public $id_estacao_tipo;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $nome;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $descricao;
    
}


