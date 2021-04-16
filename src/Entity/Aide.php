<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aide
 *
 * @ORM\Table(name="aide")
 * @ORM\Entity
 */
class Aide
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=100, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sujet;

    /**
     * @var int
     *
     * @ORM\Column(name="utilisateur_id", type="integer", nullable=false)
     */
    private $utilisateurId;

    /**
     * @var string
     *
     * @ORM\Column(name="probleme", type="string", length=100, nullable=false)
     */
    private $probleme;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100, nullable=false)
     */
    private $mail;


}
