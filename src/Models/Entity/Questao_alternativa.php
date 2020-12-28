<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="questao_alternativa")
 **/
class Questao_alternativa {

    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id_questao_alternativa;
    
    /**
     * @var string
     * @Column(type="string") 
     */
    public $texto;
    
    /**
     * @var boolean
     * @Column(type="boolean") 
     */
    public $correta;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_questao;
    
    public function getIdQuestaoAlternativa(){
        return $this->id_questao_alternativa;
    }
    
    public function getTexto(){
        return $this->texto;
    }
    
    public function getCorreta(){
        return $this->correta;
    }
    
    
    public function getIdQuestao(){
        return $this->id_questao;
    }
    
    
}
