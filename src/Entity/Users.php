<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"mail"}, message="L'email que vous avez indiqué est déjà utilisé !")
 * @ORM\Entity
 */

class Users implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Prénom trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Nom trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Regex(pattern="/[0-9]{10}/", message="Seul les chiffre son autorisé")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\Regex(pattern="/[0-9]{5}/", message="Seul les chiffre son autorisé")
     * @Assert\NotBlank
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $mail;

    /**
     * @Assert\Length(max=4096)
     * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe n'est pas identique")
     * @Assert\NotBlank
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Length(min="6", minMessage="Votre mot de passe doit faire minimum 6 caractères")
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, inversedBy="participant")
     */
    private $participation;

    /**
     * @ORM\ManyToOne(targetEntity=Authorization::class, inversedBy="user")
     */
    private $authorization;

    /**
     * @ORM\OneToMany(targetEntity=ValidationAuthorization::class, mappedBy="user")
     */
    private $validAutho;

    /**
     * @ORM\ManyToMany(targetEntity=Structure::class, mappedBy="staff")
     */
    private $staffStructures;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="user")
     */
    private $Reports;

    /**
     * @ORM\ManyToMany(targetEntity=InterventionArea::class, inversedBy="users")
     */
    private $area;

    /**
     * @ORM\ManyToMany(targetEntity=Evenement::class, mappedBy="userFavorites")
     */
    private $favorites;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="creator")
     */
    private $creatorEvent;

    public function __construct()
    {
        $this->roles = [];
        $this->participation = new ArrayCollection();
        $this->validAutho = new ArrayCollection();
        $this->staffStructures = new ArrayCollection();
        $this->Reports = new ArrayCollection();
        $this->area = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->creatorEvent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getphone(): ?string
    {
        return $this->phone;
    }

    public function setphone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setUsername($mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getParticipation(): Collection
    {
        return $this->participation;
    }

    public function addParticipation(Evenement $participation): self
    {
        if (!$this->participation->contains($participation)) {
            $this->participation[] = $participation;
        }

        return $this;
    }

    public function removeParticipation(Evenement $participation): self
    {
        if ($this->participation->contains($participation)) {
            $this->participation->removeElement($participation);
        }

        return $this;
    }

    public function getAuthorization(): ?Authorization
    {
        return $this->authorization;
    }

    public function setAuthorization(?Authorization $authorization): self
    {
        $this->authorization = $authorization;

        return $this;
    }

    /**
     * @return Collection|ValidationAuthorization[]
     */
    public function getValidationAuthorizations(): Collection
    {
        return $this->validAutho;
    }

    public function addValidAutho(ValidationAuthorization $validAutho): self
    {
        if (!$this->validAutho->contains($validAutho)) {
            $this->validAutho[] = $validAutho;
            $validAutho->setUser($this);
        }

        return $this;
    }

    public function removeValidAutho(ValidationAuthorization $validAutho): self
    {
        if ($this->validAutho->contains($validAutho)) {
            $this->validAutho->removeElement($validAutho);
            // set the owning side to null (unless already changed)
            if ($validAutho->getUser() === $this) {
                $validAutho->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStaffStructures(): Collection
    {
        return $this->staffStructures;
    }

    public function addStaffStructure(Structure $staffStructure): self
    {
        if (!$this->staffStructures->contains($staffStructure)) {
            $this->staffStructures[] = $staffStructure;
            $staffStructure->addStaff($this);
        }

        return $this;
    }

    public function removeStaffStructure(Structure $staffStructure): self
    {
        if ($this->staffStructures->contains($staffStructure)) {
            $this->staffStructures->removeElement($staffStructure);
            $staffStructure->removeStaff($this);
        }

        return $this;
    }

    /**
     * @return Collection|InterventionArea[]
     */
    public function getArea(): Collection
    {
        return $this->area;
    }

    public function addArea(InterventionArea $area): self
    {
        if (!$this->area->contains($area)) {
            $this->area[] = $area;
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Evenement $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites[] = $favorite;
            $favorite->addUserFavorite($this);
        }

        return $this;
    }

    public function removeArea(InterventionArea $area): self
    {
        if ($this->area->contains($area)) {
            $this->area->removeElement($area);
        }

        return $this;
    }

    public function removeFavorite(Evenement $favorite): self
    {
        if ($this->favorites->contains($favorite)) {
            $this->favorites->removeElement($favorite);
            $favorite->removeUserFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getCreatorEvent(): Collection
    {
        return $this->creatorEvent;
    }

    public function addCreatorEvent(Evenement $creatorEvent): self
    {
        if (!$this->creatorEvent->contains($creatorEvent)) {
            $this->creatorEvent[] = $creatorEvent;
            $creatorEvent->setCreator($this);
        }

        return $this;
    }

    public function removeCreatorEvent(Evenement $creatorEvent): self
    {
        if ($this->creatorEvent->contains($creatorEvent)) {
            $this->creatorEvent->removeElement($creatorEvent);
            // set the owning side to null (unless already changed)
            if ($creatorEvent->getCreator() === $this) {
                $creatorEvent->setCreator(null);
            }
        }

        return $this;
    }
}
