<?php

namespace App\Models\Entity;

/**
 * @Entity @Table(name="questao_desafio_jogador")
 */
class Questao_desafio_jogador{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id_questao_desafio_jogador;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_desafio_jogador;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_questao;
    
    /**
     * @var int
     * @Column(type="integer")
     */
    public $id_questao_alternativa;
    
    /**
     * @var string
     * @Column(type="string")
     */
    public $data_resposta;
    
}
