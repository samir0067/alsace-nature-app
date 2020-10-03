<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 * @SuppressWarnings(PHPMD)
 * @Vich\Uploadable()
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reportDescrip;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reportMember;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reportTimeSpent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $reportPictures;

    /**
     * @Vich\UploadableField(mapping="file_picture_report", fileNameProperty="reportPictures")
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Evenement::class, inversedBy="report", cascade={"persist", "remove"})
     */
    private $evenement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReportDescrip(): ?string
    {
        return $this->reportDescrip;
    }

    public function setReportDescrip(?string $reportDescrip): self
    {
        $this->reportDescrip = $reportDescrip;

        return $this;
    }

    public function getReportMember(): ?string
    {
        return $this->reportMember;
    }

    public function setReportMember(?string $reportMember): self
    {
        $this->reportMember = $reportMember;

        return $this;
    }

    public function getReportTimeSpent(): ?int
    {
        return $this->reportTimeSpent;
    }

    public function setReportTimeSpent(?int $reportTimeSpent): self
    {
        $this->reportTimeSpent = $reportTimeSpent;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getReportPictures(): ?string
    {
        return $this->reportPictures;
    }

    /**
     * @param string|null $reportPictures
     * @return Report
     */
    public function setReportPictures(?string $reportPictures): Report
    {
        $this->reportPictures = $reportPictures;
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
     * @return Report
     */
    public function setImageFile(?File $imageFile): Report
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

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        $this->evenement = $evenement;

        return $this;
    }
}
