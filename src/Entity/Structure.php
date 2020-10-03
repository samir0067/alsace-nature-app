<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository", repositoryClass=StructureRepository::class)
 * @UniqueEntity(fields={"completeName"}, message="Structure {{ value }} déjà enregistré")
 * @UniqueEntity(fields={"officeMail"}, message="Email {{ value }} déjà enregistré")
 * @SuppressWarnings(PHPMD)
 * @Vich\Uploadable()
 */
class Structure
{
    const STRUCTURE = [ "1", "2", "3" ];
    const FONCTION = [ "1", "2"];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(max="255", maxMessage="Nom de structure trop long {{ limit }} caractères maximum")
     */
    private $completeName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="Acronyme trop long {{ limit }} caractères maximum")
     */
    private $usualName;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(max="500", maxMessage="Description trop long {{ limit }} caractères maximum")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $logoName;

    /**
     * @Vich\UploadableField(mapping="file_logo_structure", fileNameProperty="logoName")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes="image/jpeg"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Choice(choices=Structure::STRUCTURE, message="Choisissez un type de structure valable.")
     */
    private $structureType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="Nom du site trop long {{ limit }} caractères maximum")
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(max="255", maxMessage="lien trop long {{ limit }} caractères maximum")
     */
    private $externalPaymentLink;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Prénom trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $legalRpFirstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Nom trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $legalRpLastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="L\'email '{{ value }}' n'est pas un email valide.")
     * @Assert\Length(max="255", maxMessage="Email trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $legalRepresentMail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice(choices=Structure::FONCTION, message="Choisissez une fonction valable.")
     * @Assert\NotBlank
     */
    private $legalRepresentRole;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message = "Le email '{{ value }}' n'est pas un email valide.")
     * @Assert\Length(max="255", maxMessage="Email trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $officeMail;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex("/^[0-9]{9}$/")
     * @Assert\NotBlank
     * @Assert\Positive
     */
    private $officePhone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Adresse trop longue {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $officeAddress;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Nom de département trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $territoryOffice;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="structure", cascade={"persist"})
     * @ORM\Column(nullable=true)
     */
    private $evenements;

    /**
     * @ORM\ManyToMany(targetEntity=InterventionArea::class, inversedBy="structures", cascade={"persist"})
     */
    private $interventionArea;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex("/^[0-9]{5}$/")
     * @Assert\Positive
     * @Assert\NotBlank
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(max="255", maxMessage="Nom de commune trop long {{ limit }} caractères maximum")
     * @Assert\NotBlank
     */
    private $city;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, mappedBy="structures")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ValidationAuthorization::class, mappedBy="structure")
     */
    private $validationAuthorizations;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="staffStructures", cascade={"persist"})
     */
    private $staff;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->interventionArea = new ArrayCollection();
        $this->user = new ArrayCollection();
        $this->validationAuthorizations = new ArrayCollection();
        $this->staff = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompleteName(): ?string
    {
        return $this->completeName;
    }

    public function setCompleteName(string $completeName): self
    {
        $this->completeName = $completeName;

        return $this;
    }

    public function getUsualName(): ?string
    {
        return $this->usualName;
    }

    public function setUsualName(?string $usualName): self
    {
        $this->usualName = $usualName;

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

    public function getLogoName(): ?string
    {
        return $this->logoName;
    }

    public function setLogoName(?string $logoName): self
    {
        $this->logoName = $logoName;

        return $this;
    }

    public function getStructureType(): ?string
    {
        return $this->structureType;
    }

    public function setStructureType(string $structureType): self
    {
        $this->structureType = $structureType;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getExternalPaymentLink(): ?string
    {
        return $this->externalPaymentLink;
    }

    public function setExternalPaymentLink(?string $externalPaymentLink): self
    {
        $this->externalPaymentLink = $externalPaymentLink;

        return $this;
    }

    public function getlegalRpFirstName(): ?string
    {
        return $this->legalRpFirstName;
    }

    public function setlegalRpFirstName(string $legalRpFirstName): self
    {
        $this->legalRpFirstName = $legalRpFirstName;

        return $this;
    }

    public function getlegalRpLastName(): ?string
    {
        return $this->legalRpLastName;
    }

    public function setlegalRpLastName(string $legalRpLastName): self
    {
        $this->legalRpLastName = $legalRpLastName;

        return $this;
    }

    public function getLegalRepresentMail(): ?string
    {
        return $this->legalRepresentMail;
    }

    public function setLegalRepresentMail(string $legalRepresentMail): self
    {
        $this->legalRepresentMail = $legalRepresentMail;

        return $this;
    }

    public function getLegalRepresentRole(): ?string
    {
        return $this->legalRepresentRole;
    }

    public function setLegalRepresentRole(string $legalRepresentRole): self
    {
        $this->legalRepresentRole = $legalRepresentRole;

        return $this;
    }

    public function getOfficeMail(): ?string
    {
        return $this->officeMail;
    }

    public function setOfficeMail(string $officeMail): self
    {
        $this->officeMail = $officeMail;

        return $this;
    }

    public function getOfficePhone(): ?int
    {
        return $this->officePhone;
    }

    public function setOfficePhone(int $officePhone): self
    {
        $this->officePhone = $officePhone;

        return $this;
    }

    public function getOfficeAddress(): ?string
    {
        return $this->officeAddress;
    }

    public function setOfficeAddress(string $officeAddress): self
    {
        $this->officeAddress = $officeAddress;

        return $this;
    }

    public function getTerritoryOffice(): ?string
    {
        return $this->territoryOffice;
    }

    public function setTerritoryOffice(string $territoryOffice): self
    {
        $this->territoryOffice = $territoryOffice;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements()
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setStructure($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->contains($evenement)) {
            $this->evenements->removeElement($evenement);
            // set the owning side to null (unless already changed)
            if ($evenement->getStructure() === $this) {
                $evenement->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InterventionArea[]
     */
    public function getInterventionArea(): Collection
    {
        return $this->interventionArea;
    }

    public function addInterventionArea(InterventionArea $interventionArea): self
    {
        if (!$this->interventionArea->contains($interventionArea)) {
            $this->interventionArea[] = $interventionArea;
        }

        return $this;
    }

    public function removeInterventionArea(InterventionArea $interventionArea): self
    {
        if ($this->interventionArea->contains($interventionArea)) {
            $this->interventionArea->removeElement($interventionArea);
        }

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Structure
     */
    public function setImageFile(?File $imageFile): Structure
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
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
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }

    /**
     * @return Collection|ValidationAuthorization[]
     */
    public function getValidationAuthorizations(): Collection
    {
        return $this->validationAuthorizations;
    }

    public function addValidationAuthorization(ValidationAuthorization $validationAuthorization): self
    {
        if (!$this->validationAuthorizations->contains($validationAuthorization)) {
            $this->validationAuthorizations[] = $validationAuthorization;
            $validationAuthorization->setStructure($this);
        }

        return $this;
    }

    public function removeValidationAuthorization(ValidationAuthorization $validationAuthorization): self
    {
        if ($this->validationAuthorizations->contains($validationAuthorization)) {
            $this->validationAuthorizations->removeElement($validationAuthorization);
            // set the owning side to null (unless already changed)
            if ($validationAuthorization->getStructure() === $this) {
                $validationAuthorization->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getStaff(): Collection
    {
        return $this->staff;
    }

    public function addStaff(Users $staff): self
    {
        if (!$this->staff->contains($staff)) {
            $this->staff[] = $staff;
        }

        return $this;
    }

    public function removeStaff(Users $staff): self
    {
        if ($this->staff->contains($staff)) {
            $this->staff->removeElement($staff);
        }

        return $this;
    }
}
