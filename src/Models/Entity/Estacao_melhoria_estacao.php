<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="estacao_melhoria_estacao")
 **/
class Estacao_melhoria_estacao {

    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id_estacao_melhoria_estacao;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $quantidade  ;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_estacao;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_estacao_melhoria;
    
     /**
     * @var boolean
     * @Column(type="boolean")
     **/
    public $esta_construindo;
    
     /**
     * @var string
     * @Column(type="string")
     **/
    public $inicio_construcao;
    
     /**
     * @var string
     * @Column(type="string")
     **/
    public $fim_construcao;
    
    /**
     * @ManyToOne(targetEntity="Estacao_melhoria")
     * @JoinColumn(name="id_estacao_melhoria", referencedColumnName="id_estacao_melhoria")
     */
    public $estacao_melhoria;
    
    public function __construct(){
        $this->estacao_melhoria = new Estacao_melhoria();
    }
    
}