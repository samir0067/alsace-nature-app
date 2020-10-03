<?php

namespace App\Entity;

use App\Repository\ValidationAuthorizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ValidationAuthorizationRepository::class)
 */
class ValidationAuthorization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isValide;

    /**
     * @ORM\ManyToOne(targetEntity=Structure::class, inversedBy="validationAuthorizations")
     */
    private $structure;

    /**
     * @ORM\ManyToOne(targetEntity=Authorization::class, inversedBy="validationAuthorizations")
     */
    private $authorization;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="validationAuthorizations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=InterventionArea::class, inversedBy="validationAuthos")
     */
    private $interventionArea;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsValide(): ?bool
    {
        return $this->isValide;
    }

    public function setIsValide(bool $isValide): self
    {
        $this->isValide = $isValide;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getInterventionArea(): ?InterventionArea
    {
        return $this->interventionArea;
    }

    public function setInterventionArea(?InterventionArea $interventionArea): self
    {
        $this->interventionArea = $interventionArea;

        return $this;
    }
}
