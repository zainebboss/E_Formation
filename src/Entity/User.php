<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\GroupInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UserRepository;

/**
 * User
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email",
 * message="Cet email est déja utilisé"
 * )
 *
 * */
class User implements  UserInterface
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
     * @ORM\Column(name="email", type="string", length=1024, nullable=false)
     * @Assert\Email(
     * message="Votre email  est invalide"
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=1024, nullable=false)
     * @Assert\Regex(
     * pattern="/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
     * message="Le mot de passe doit contenir au minimum 8 caractères dont au moins une lettre en majuscule, une lettre en miniscule et un chiffre"
     * )
     */
    private $password;
    /**
     * @var string
     * @Assert\Regex(
     * pattern="/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
     * message="Votre mot de passe doit comporter minimum 8  caractères et contenir une majuscule et un chiffreLe mot de passe doit contenir au minimum 8 caractères dont au moins une lettre en majuscule, une lettre en miniscule et un chiffre"
     * )
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=true)
     */
    private $role;
    /**
     * @var GroupInterface[]|Collection
     */
    protected $groups;
    /**
     * @var array
     * @ORM\Column(name="roles", type="array", nullable=false)

     */
    protected $roles;
    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=20, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenom", type="string", length=20, nullable=true)
     */
    private $prenom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="telephone", type="string", length=110, nullable=true)
     */
    private $telephone;

    /**
     * @var string|null
     *
     * @ORM\Column(name="adresse", type="string", length=200, nullable=true)
     */
    private $adresse;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_naissance", type="date", nullable=true)
     */
    private $dateNaissance;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable", type="boolean", nullable=false)
     */
    private $enable;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="apprenant", orphanRemoval=true)
     */
    private $avisApprenant;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="formateur", orphanRemoval=true)
     */
    private $avisFormateur;

    /**
     * @ORM\OneToMany(targetEntity=Inscription::class, mappedBy="utilisateur")
     */
    private $inscriptions;
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->enable = true;
        $this->roles = array();
        $this->avisApprenant = new ArrayCollection();
        $this->avisFormateur = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword( $plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return string|null
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @return string|null
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return string|null
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateNaissance(): ?\DateTime
    {
        return $this->dateNaissance;
    }

    /**
     * @return bool
     */
    public function isEnable()
    {
        return $this->enable;
    }

    /**
     * @param int $id
     */
    public function setId( $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $email
     */
    public function setEmail( $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $password
     */
    public function setPassword( $password)
    {
        $this->password = $password;
    }

    /**
     * @param string $role
     */
    public function setRole( $role)
    {
        $this->role = $role;
    }

    /**
     * @param string|null $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom( $prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone( $telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @param string|null $adresse
     */
    public function setAdresse( $adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @param \DateTime|null $dateNaissance
     */
    public function setDateNaissance(?\DateTime $dateNaissance): void
    {
        $this->dateNaissance = $dateNaissance;
    }

    /**
     * @param bool $enable
     */
    public function setEnable(bool $enable): void
    {
        $this->enable = $enable;
    }


    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
      //  $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }
    public function addRole($role)
    {
        $role = strtoupper($role);
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }
    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getGroupNames()
    {
        $names = array();
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroup($name)
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup(GroupInterface $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup(GroupInterface $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvisApprenant()
    {
        return $this->avisApprenant;
    }

    public function addAvisApprenant( $avisApprenant)
    {
        if (!$this->avisApprenant->contains($avisApprenant)) {
            $this->avisApprenant[] = $avisApprenant;
            $avisApprenant->setApprenant($this);
        }

        return $this;
    }

    public function removeAvisApprenant( $avisApprenant)
    {
        if ($this->avisApprenant->removeElement($avisApprenant)) {
            // set the owning side to null (unless already changed)
            if ($avisApprenant->getApprenant() === $this) {
                $avisApprenant->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvisFormateur()
    {
        return $this->avisFormateur;
    }

    public function addAvisFormateur( $avisFormateur)
    {
        if (!$this->avisFormateur->contains($avisFormateur)) {
            $this->avisFormateur[] = $avisFormateur;
            $avisFormateur->setFormateur($this);
        }

        return $this;
    }

    public function removeAvisFormateur( $avisFormateur)
    {
        if ($this->avisFormateur->removeElement($avisFormateur)) {
            // set the owning side to null (unless already changed)
            if ($avisFormateur->getFormateur() === $this) {
                $avisFormateur->setFormateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setUtilisateur($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->removeElement($inscription)) {
            // set the owning side to null (unless already changed)
            if ($inscription->getUtilisateur() === $this) {
                $inscription->setUtilisateur(null);
            }
        }

        return $this;
    }
}
