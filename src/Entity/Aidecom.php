<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Aidecom
 *
 * @ORM\Table(name="aidecom", indexes={@ORM\Index(name="id_sujet", columns={"id_sujet"})})
 * @ORM\Entity
 */
class Aidecom
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
     * @return int
     */
    public function getId(): int
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
    public function getCommaintre(): ?string
    {
        return $this->commaintre;
    }

    /**
     * @param string $commaintre
     */
    public function setCommaintre(string $commaintre): void
    {
        $this->commaintre = $commaintre;
    }

    /**
     * @return int
     */
    public function getIdSujet(): ?int
    {
        return $this->idSujet;
    }

    /**
     * @param int $idSujet
     */
    public function setIdSujet(int $idSujet): void
    {
        $this->idSujet = $idSujet;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="commaintre", type="string", length=500, nullable=false)
     * @Assert\Length(min="8",
     * max="100", minMessage="doit contenir au min {{ limit }}",
     * maxMessage="doit contenir au min {{ limit }}" )
     */
    private $commaintre;

    /**
     * @var int
     *
     * @ORM\Column(name="id_sujet", type="integer", nullable=false)
     */
    private $idSujet;


}
