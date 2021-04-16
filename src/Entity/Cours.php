<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cours
 *
 * @ORM\Table(name="cours", indexes={@ORM\Index(name="id_formation", columns={"formation_id"})})
 * @ORM\Entity
 */
class Cours
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=1024, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="fichier", type="string", length=1024, nullable=false)
     */
    private $fichier;

    /**
     * @var int
     *
     * @ORM\Column(name="formation_id", type="integer", nullable=false)
     */
    private $formationId;

    /**
     * @var string
     *
     * @ORM\Column(name="Description_cat", type="string", length=255, nullable=false)
     */
    private $descriptionCat;


}
