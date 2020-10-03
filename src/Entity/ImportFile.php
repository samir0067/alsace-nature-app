<?php

namespace App\Entity;

use App\Repository\ImportFileRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ImportFileRepository::class)
 * @Vich\Uploadable()
 * @SuppressWarnings(PHPMD)
 */
class ImportFile
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
    private $filesCsv;

    /**
     * @Vich\UploadableField(mapping="file_csv_event", fileNameProperty="filesCsv")
     * @var File
     */
    private $filesCsvFile;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $updatedAt;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileCsv(): ?string
    {
        return $this->filesCsv;
    }

    public function setFileCsv(string $fileCsv): self
    {
        $this->filesCsv = $fileCsv;

        return $this;
    }


    public function setFilesCsvFile(File $file)
    {
        $this->filesCsvFile = $file;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($file != null) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getFilesCsvFile()
    {
        return $this->filesCsvFile;
    }

    public function setFilesCsv($file)
    {
        $this->filesCsv = $file;
    }

    public function getFilesCsv()
    {
        return $this->filesCsv;
    }
}
