<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\AideRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * @ORM\Table(name="aide")
 * @ORM\Entity(repositoryClass=AideRepository::class)
 * @UniqueEntity(fields={"mail"},
 * message="le champs il faut etre unique ")
 * @ParamConverter
 */
class Aide
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    protected $captchaCode;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=100, nullable=false)
     * @Assert\Length(min="3",
     * max="100", minMessage="doit contenir au min {{ limit }}",
     * maxMessage="doit contenir au min {{ limit }}" )
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="probleme", type="string", length=100, nullable=false)
     * @Assert\Length(min="3",
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

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
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

    public function __construct()
    {
        $this->date = new \DateTime('now');
    }

}
