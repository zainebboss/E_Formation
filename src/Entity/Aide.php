<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Aide
 *
 * @ORM\Table(name="aide")
 * @ORM\Entity
 * @UniqueEntity(fields={"mail"},
 * message="le champs il faut etre unique ")
 */
class Aide
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
     * @ORM\Column(name="sujet", type="string", length=100, nullable=false)
     * @Assert\Length(min="8",
     * max="100", minMessage="doit contenir au min {{ limit }}",
     * maxMessage="doit contenir au min {{ limit }}" )
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="probleme", type="string", length=100, nullable=false)
     * @Assert\Length(min="8",
     * max="100", minMessage="doit contenir au min {{ limit }}",
     * maxMessage="doit contenir au min {{ limit }}" )
     */
    private $probleme;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     */
    public function setSujet(string $sujet): void
    {
        $this->sujet = $sujet;
    }

    /**
     * @return string
     */
    public function getProbleme(): ?string
    {
        return $this->probleme;
    }

    /**
     * @param string $probleme
     */
    public function setProbleme(string $probleme): void
    {
        $this->probleme = $probleme;
    }

    /**
     * @return string
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail(string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @var string
     * @ORM\Column(name="mail", type="string", length=100, nullable=false)
     * @Assert\Email(message=" email {{ value }} est non valide")
     * @Assert\Length(min="8",
     * max="30", minMessage="doit contenir au min {{ limit }}",
     * maxMessage="doit contenir au min {{ limit }}" )
     */
    private $mail;


}
