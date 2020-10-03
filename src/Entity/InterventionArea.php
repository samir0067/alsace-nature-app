<?php

namespace App\Entity;

use App\Repository\InterventionAreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterventionAreaRepository::class)
 */
class InterventionArea
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $interventionArea;

    /**
     * @ORM\ManyToMany(targetEntity=Structure::class, mappedBy="interventionArea")
     */
    private $structures;

    /**
     * @ORM\OneToMany(targetEntity=ValidationAuthorization::class, mappedBy="interventionArea")
     */
    private $validationAuthos;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, mappedBy="area")
     */
    private $users;

    public function __construct()
    {
        $this->structures = new ArrayCollection();
        $this->validationAuthos = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInterventionArea(): ?int
    {
        return $this->interventionArea;
    }

    public function setInterventionArea(int $interventionArea): self
    {
        $this->interventionArea = $interventionArea;

        return $this;
    }

    /**
     * @return Collection|Structure[]
     */
    public function getStructures(): Collection
    {
        return $this->structures;
    }

    public function addStructure(Structure $structure): self
    {
        if (!$this->structures->contains($structure)) {
            $this->structures[] = $structure;
            $structure->addInterventionArea($this);
        }

        return $this;
    }

    public function removeStructure(Structure $structure): self
    {
        if ($this->structures->contains($structure)) {
            $this->structures->removeElement($structure);
            $structure->removeInterventionArea($this);
        }

        return $this;
    }

    /**
     * @return Collection|ValidationAuthorization[]
     */
    public function getValidationAuthorizations(): Collection
    {
        return $this->validationAuthos;
    }

    public function addValidationAuthorization(ValidationAuthorization $validationAutho): self
    {
        if (!$this->validationAuthos->contains($validationAutho)) {
            $this->validationAuthos[] = $validationAutho;
            $validationAutho->setInterventionArea($this);
        }

        return $this;
    }

    public function removeValidationAuthorization(ValidationAuthorization $validationAutho): self
    {
        if ($this->validationAuthos->contains($validationAutho)) {
            $this->validationAuthos->removeElement($validationAutho);
            // set the owning side to null (unless already changed)
            if ($validationAutho->getInterventionArea() === $this) {
                $validationAutho->setInterventionArea(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addArea($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeArea($this);
        }

        return $this;
    }
}
