<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="professor")
 */
class Professor{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_professor;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $nome;
    
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
    
    /**
     * @OneToMany(targetEntity="Turma", mappedBy="Professor", cascade={"persist", "remove"}) 
     */
    public $turmas;
    
    public function login ($passWord){
        if($this->senha === sha1($passWord)){
            return true;
        }
        return false;
    }
}

