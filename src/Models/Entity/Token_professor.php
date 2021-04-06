<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="token_professor")
 **/
class Token_professor{
    
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    public $id_token;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $token;
    
    /**
     * @var string
     * @Column(type="string")
     **/
    public $refresh_token;
    
    /**
     * @var DateTime
     * @Column(type="datetime")
     **/
    public $validade;
    
    /**
     * @var int
     * @Column(type="integer")
     **/
    public $id_professor;
    
   
}


