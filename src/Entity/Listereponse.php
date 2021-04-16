<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listereponse
 *
 * @ORM\Table(name="listereponse", indexes={@ORM\Index(name="idQuestion", columns={"idQuestion"}), @ORM\Index(name="idReponse", columns={"idReponse"})})
 * @ORM\Entity
 */
class Listereponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="idUser", type="integer", nullable=false)
     */
    private $iduser;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQuestion", referencedColumnName="id")
     * })
     */
    private $idquestion;

    /**
     * @var \Reponse
     *
     * @ORM\ManyToOne(targetEntity="Reponse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idReponse", referencedColumnName="id")
     * })
     */
    private $idreponse;


}
