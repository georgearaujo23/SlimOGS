<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="estacao_melhoria")
 **/
class Estacao_melhoria {

    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id_estacao_melhoria;

    /**
     * @var string
     * @Column(type="string") 
     */
    public $nome;

    /**
     * @var string
     * @Column(type="string") 
     */
    public $descricao;


    /**
     * @var int
     * @Column(type="integer") 
     */
    public $custo_moedas;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $energia;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $comida;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $agua;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $populacao;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $sustentabilidade;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $sabedoria;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $sabedoria_pesquisa;
    
    /**
     * @var boolean
     * @Column(type="boolean") 
     */
    public $pesquisado;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_estacao_tipo;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_estacao_melhoria_relacionada;
    
    public $estacao_tipo;
    
    public function __construct(){
        $this->estacao_tipo = new Estacao_tipo();
    }
    
}