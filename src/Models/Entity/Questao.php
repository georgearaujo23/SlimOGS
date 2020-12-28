<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="questao")
 **/
class Questao {

    /**
     * @var int
     * @Id @Column(type="integer") 
     * @GeneratedValue
     */
    public $id_questao;

    /**
     * @var string
     * @Column(type="string") 
     */
    public $enunciado;

    /**
     * @var int
     * @Column(type="integer") 
     */
    public $id_assunto;
    
    /**
     * @var int
     * @Column(type="integer") 
     */
    public $nivel;
    
    /** 
     * @OneToMany(targetEntity="Questao_alternativa", mappedBy="Questao", cascade={"persist", "remove"}) 
     */
    public $alternativas;
    
    /**
     * @OneToMany(targetEntity="Questao_aluno", mappedBy="Questao", cascade={"persist", "remove"}) 
     */
    public $respostas;

    public function __construct(){
        $this->alternativas = new ArrayCollection();
    }
    
    
    public function getIdQuestao(){
        return $this->id_questao;
    }

    public function getEnunciado(){
        return $this->enunciado;
    }

    public function getIdAssunto() {
        return $this->id_assunto;
    }    

    public function getNivel() {
        return $this->nivel;
    }    
    
    public function getAlternativas() {
        return $this->alternativas;
    }  
    
    public function setEnunciado($enunciado){
        $this->enunciado = $enunciado;
        return $this;  
    }

    public function setIdAssunto($id_assunto) {
        $this->id_assunto = $id_assunto;
        return $this;
    }

    public function setNivel($nivel) {
        $this->nivel = $nivel;
        return $this;
    }
    
    public function setAlternativas($alternativas) {
        $this->alternativas = $alternativas;
        return $this;
    } 
}