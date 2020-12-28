<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="assunto")
 **/
class Assunto{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_assunto;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $descricao;
    
}

