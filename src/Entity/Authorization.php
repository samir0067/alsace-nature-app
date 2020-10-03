<?php

namespace App\Entity;

use App\Repository\AuthorizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuthorizationRepository::class)
 */
class Authorization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="authorization")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ValidationAuthorization::class, mappedBy="authorization")
     */
    private $validAutho;


    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->validAutho = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Users $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setAuthorization($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAuthorization() === $this) {
                $user->setAuthorization(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ValidationAuthorization[]
     */
    public function getValidAutho(): Collection
    {
        return $this->validAutho;
    }

    public function addValidAutho(ValidationAuthorization $validAutho): self
    {
        if (!$this->validAutho->contains($validAutho)) {
            $this->validAutho[] = $validAutho;
            $validAutho->setAuthorization($this);
        }

        return $this;
    }

    public function removeValidAutho(ValidationAuthorization $validAutho): self
    {
        if ($this->validAutho->contains($validAutho)) {
            $this->validAutho->removeElement($validAutho);
            // set the owning side to null (unless already changed)
            if ($validAutho->getAuthorization() === $this) {
                $validAutho->setAuthorization(null);
            }
        }

        return $this;
    }
}
