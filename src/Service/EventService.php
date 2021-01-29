<?php 
namespace App\Service;

use App\Repository\EventRepository;
use App\Entity\Event;

class EventService
{
    private $eventRepository;

    public function __construct(
        EventRepository $eventRepository
    )
    {
        $this->eventRepository = $eventRepository;
    }

    public function create(
        string $title, 
        $date_start, 
        $date_end, 
        string $description
    )
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        $event = new Event(
            $title, 
            $date_start, 
            $date_end,
            $description,
            $now
        );

        $this->eventRepository->save($event);

    }
}