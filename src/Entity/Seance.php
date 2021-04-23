<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seance
 *
 * @ORM\Table(name="seance")

 * @ORM\Entity


 */
class Seance
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_seance", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSeance;

    /**
     * @var int
     *
     * @ORM\Column(name="ID_formation", type="integer", nullable=false)
     */
    private $idFormation;

    /**
     * @var string
     *
     * @ORM\Column(name="lien", type="string", length=1024, nullable=false)
     */
    private $lien;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1024, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="Date_seance", type="string", length=1024, nullable=false)
     */
    private $dateSeance;

    /**
     * @return int
     */
    public function getIdSeance(): ?int
    {
        return $this->idSeance;
    }

    /**
     * @param int $idSeance
     */
    public function setIdSeance(int $idSeance): void
    {
        $this->idSeance = $idSeance;
    }

    /**
     * @return int
     */
    public function getIdFormation(): ?int
    {
        return $this->idFormation;
    }

    /**
     * @param int $idFormation
     */
    public function setIdFormation(int $idFormation): void
    {
        $this->idFormation = $idFormation;
    }

    /**
     * @return string
     */
    public function getLien(): ?string
    {
        return $this->lien;
    }

    /**
     * @param string $lien
     */
    public function setLien(string $lien): void
    {
        $this->lien = $lien;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDateSeance(): ?string
    {
        return $this->dateSeance;
    }

    /**
     * @param string $dateSeance
     */
    public function setDateSeance(string $dateSeance): void
    {
        $this->dateSeance = $dateSeance;
    }


}
