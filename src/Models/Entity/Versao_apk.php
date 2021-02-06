<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="versao_apk")
 **/
class Versao_apk {

    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id_versao;
    
    /**
     * @var string
     * @Column(type="string") 
     */
    public $numero  ;
    
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
    
     /**
     * @var boolean
     * @Column(type="boolean")
     **/
    public $ativo;
    
    
}