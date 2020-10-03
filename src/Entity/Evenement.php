<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 * @SuppressWarnings(PHPMD)
 * @Vich\Uploadable()
 */
class Evenement
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $organisatorPhoneNum;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $organisatorMail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeUsers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $targetAudience;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $accessibility;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxParticipants;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numberSubscribAdult;

    /**
     * @ORM\Column(type="boolean")
     */
    private $registerRequired;

    /**
     * @ORM\Column(type="boolean")
     */
    private $cost;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $initialPriceAdult;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $initialPriceChild;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="date")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStop;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $illustration;

    /**
     * @Vich\UploadableField(mapping="file_img_event", fileNameProperty="illustration")
     * @var File|null
     * @Assert\Image(
     *     mimeTypes="image/jpeg"
     * )
     */
    private $imageFile;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="evenements", cascade={"persist", "remove"})
     */
    private $category;

    /**
     * @ORM\ManyToMany(targetEntity=Theme::class, inversedBy="evenements", cascade={"persist", "remove"})
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity=Structure::class, inversedBy="evenements", cascade={"persist", "remove"})
     */
    private $structure;

    /**
     * @ORM\OneToMany(targetEntity=Children::class, mappedBy="evenement")
     */
    private $childrens;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reduction;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, mappedBy="participation")
     */
    private $participant;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrParticipants;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reductionCondition;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, inversedBy="favorites")
     */
    private $userFavorites;

    /**

     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="creatorEvent")
     */
    private $creator;

    /**
     * @ORM\OneToOne(targetEntity=Report::class, mappedBy="evenement", cascade={"persist", "remove"})
     */
    private $report;


    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->theme = new ArrayCollection();
        $this->childrens = new ArrayCollection();
        $this->participant = new ArrayCollection();
        $this->userFavorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getOrganisatorPhoneNum(): ?int
    {
        return $this->organisatorPhoneNum;
    }

    public function setOrganisatorPhoneNum(int $organisatorPhoneNum): self
    {
        $this->organisatorPhoneNum = $organisatorPhoneNum;

        return $this;
    }

    public function getOrganisatorMail(): ?string
    {
        return $this->organisatorMail;
    }

    public function setOrganisatorMail(string $organisatorMail): self
    {
        $this->organisatorMail = $organisatorMail;

        return $this;
    }

    public function getTypeUsers(): ?string
    {
        return $this->typeUsers;
    }

    public function setTypeUsers(string $typeUsers): self
    {
        $this->typeUsers = $typeUsers;

        return $this;
    }

    public function getTargetAudience(): ?string
    {
        return $this->targetAudience;
    }

    public function setTargetAudience(string $targetAudience): self
    {
        $this->targetAudience = $targetAudience;

        return $this;
    }

    public function getAccessibility(): ?string
    {
        return $this->accessibility;
    }

    public function setAccessibility(string $accessibility): self
    {
        $this->accessibility = $accessibility;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    public function setMaxParticipants(int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function getNumberSubscribAdult(): ?int
    {
        return $this->numberSubscribAdult;
    }

    public function setNumberSubscribAdult(int $numberSubscribAdult): self
    {
        $this->numberSubscribAdult = $numberSubscribAdult;

        return $this;
    }

    public function getRegisterRequired(): ?bool
    {
        return $this->registerRequired;
    }

    public function setRegisterRequired(bool $registerRequired): self
    {
        $this->registerRequired = $registerRequired;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getInitialPriceAdult(): ?int
    {
        return $this->initialPriceAdult;
    }

    public function setInitialPriceAdult(?int $initialPriceAdult): self
    {
        $this->initialPriceAdult = $initialPriceAdult;

        return $this;
    }

    public function getInitialPriceChild(): ?int
    {
        return $this->initialPriceChild;
    }

    public function setInitialPriceChild(?int $initialPriceChild): self
    {
        $this->initialPriceChild = $initialPriceChild;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateStop(): ?\DateTimeInterface
    {
        return $this->dateStop;
    }

    public function setDateStop(\DateTimeInterface $dateStop): self
    {
        $this->dateStop = $dateStop;

        return $this;
    }

    public function getEventStatus(): ?string
    {
        return $this->eventStatus;
    }

    public function setEventStatus(?string $eventStatus): self
    {
        $this->eventStatus = $eventStatus;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->category->contains($category)) {
            $this->category->removeElement($category);
        }

        return $this;
    }

    /**
     * @return Collection|Theme[]
     */
    public function getTheme(): Collection
    {
        return $this->theme;
    }

    public function addTheme(Theme $theme): self
    {
        if (!$this->theme->contains($theme)) {
            $this->theme[] = $theme;
        }

        return $this;
    }

    public function removeTheme(Theme $theme): self
    {
        if ($this->theme->contains($theme)) {
            $this->theme->removeElement($theme);
        }

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

    /**
     * @return Collection|Children[]
     */
    public function getChildrens(): Collection
    {
        return $this->childrens;
    }

    public function addChildren(Children $children): self
    {
        if (!$this->childrens->contains($children)) {
            $this->childrens[] = $children;
            $children->setEvenement($this);
        }

        return $this;
    }

    public function removeChildren(Children $children): self
    {
        if ($this->childrens->contains($children)) {
            $this->childrens->removeElement($children);
            // set the owning side to null (unless already changed)
            if ($children->getEvenement() === $this) {
                $children->setEvenement(null);
            }
        }

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

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

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getReduction(): ?int
    {
        return $this->reduction;
    }

    public function setReduction(?int $reduction): self
    {
        $this->reduction = $reduction;

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
     * @return Evenement
     */
    public function setImageFile(?File $imageFile): Evenement
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
    public function getParticipant(): Collection
    {
        return $this->participant;
    }

    public function addParticipant(Users $participant): self
    {
        if (!$this->participant->contains($participant)) {
            $this->participant[] = $participant;
            $participant->addParticipation($this);
        }

        return $this;
    }

    public function removeParticipant(Users $participant): self
    {
        if ($this->participant->contains($participant)) {
            $this->participant->removeElement($participant);
            $participant->removeParticipation($this);
        }

        return $this;
    }

    public function getNbrParticipants(): ?int
    {
        return $this->nbrParticipants;
    }

    public function setNbrParticipants(?int $nbrParticipants): self
    {
        $this->nbrParticipants = $nbrParticipants;

        return $this;
    }

    public function getReductionCondition(): ?string
    {
        return $this->reductionCondition;
    }

    public function setReductionCondition(?string $reductionCondition): self
    {
        $this->reductionCondition = $reductionCondition;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUserFavorites(): Collection
    {
        return $this->userFavorites;
    }

    public function addUserFavorite(Users $userFavorite): self
    {
        if (!$this->userFavorites->contains($userFavorite)) {
            $this->userFavorites[] = $userFavorite;
        }

        return $this;
    }

    public function removeUserFavorite(Users $userFavorite): self
    {
        if ($this->userFavorites->contains($userFavorite)) {
            $this->userFavorites->removeElement($userFavorite);
        }

        return $this;
    }

    public function getCreator(): ?Users
    {
        return $this->creator;
    }

    public function setCreator(?Users $creator): self
    {
        $this->creator = $creator;
      
        return $this;
    }

    public function getReport(): ?Report
    {
        return $this->report;
    }

    public function setReport(?Report $report): self
    {
        // unset the owning side of the relation if necessary
        if ($this->report !== null && $report === null) {
            $this->report->setEvenement(null);
        } else {
            // set the owning side of the relation if necessary
            if ($report !== null && $this !== $report->getEvenement()) {
                $report->setEvenement($this);
            }
        }
        $this->report = $report;

        return $this;
    }
}
