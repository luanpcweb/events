<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_end;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_created;

    public function __construct(
        string $title, 
        \DateTime $date_start, 
        \DateTime $date_end, 
        string $description, 
        \DateTime $date_created
    )
    {
        $this->title = $title; 
        $this->date_start = $date_start; 
        $this->date_end = $date_end; 
        $this->description = $description; 
        $this->date_created = $date_created; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->date_start;
    }

    public function getDateEnd(): ?\DateTime
    {
        return $this->date_end;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->date_created;
    }

    public function jsonSerialize()
    {
        return [
            "title" => $this->getTitle(),
            "date_start" => $this->getDateStart(),
            "date_end" => $this->getDateEnd(),
            "date_created" => $this->getDateCreated()
        ];
    }
}
