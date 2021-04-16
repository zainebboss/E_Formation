<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Examenquestion
 *
 * @ORM\Table(name="examenquestion", indexes={@ORM\Index(name="idExamen", columns={"idExamen"}), @ORM\Index(name="idQuestion", columns={"idQuestion"})})
 * @ORM\Entity
 */
class Examenquestion
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
     * @var \Examen
     *
     * @ORM\ManyToOne(targetEntity="Examen")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idExamen", referencedColumnName="id")
     * })
     */
    private $idexamen;

    /**
     * @var \Question
     *
     * @ORM\ManyToOne(targetEntity="Question")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQuestion", referencedColumnName="id")
     * })
     */
    private $idquestion;


}
