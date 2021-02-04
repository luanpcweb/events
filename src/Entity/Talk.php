<?php

namespace App\Entity;

use App\Repository\TalkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TalkRepository::class)
 */
class Talk
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $event;

    /**
     * @ORM\Column(type="time")
     */
    private $hour_start;

    /**
     * @ORM\Column(type="time")
     */
    private $hour_end;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Speaker", inversedBy="talk")
     */
    private $speaker;

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(Event $event): void
    {
        $this->event = $event;
    }

    public function getHourStart(): ?\DateTime
    {
        return $this->hour_start;
    }

    public function setHourStart(\DateTime $hour_start): void
    {
        $this->hour_start = $hour_start;
    }

    public function getHourEnd(): ?\DateTime
    {
        return $this->hour_end;
    }

    public function setHourEnd(\DateTime $hour_end): void
    {
        $this->hour_end = $hour_end;
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

    public function getSpeaker(): ?Speaker
    {
        return $this->speaker;
    }

    /**
     * @param Speaker $speaker
     */
    public function setSpeaker(Speaker $speaker): void
    {
        $this->speaker = $speaker;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): ?array
    {
        return [
            "id" => $this->getId(),
            "title" => $this->getTitle(),
            "date" => $this->getDate(),
            "hour_start" => $this->getHourStart(),
            "hour_end" => $this->getHourEnd(),
            "description" => $this->getDescription()
        ];
    }
}
