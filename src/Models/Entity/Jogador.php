<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="jogador")
 */
class Jogador{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_jogador;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $nick_name;
    
    /**
     * @var DateTime
     * @Column(type="date")
     */
    public $registro;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $email;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $senha;
    
    public function login ($passWord){
        if($this->senha === sha1($passWord)){
            return true;
        }
        return false;
    }
}

