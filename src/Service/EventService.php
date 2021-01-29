<?php 
namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Repository\EventRepository;
use App\Entity\Event;
use App\Exceptions\EventNotFound;

class EventService
{
    private $eventRepository;

    public function __construct(
        EventRepository $eventRepository
    )
    {
        $this->eventRepository = $eventRepository;
    }

    public function listAll()
    {
        $events = $this->eventRepository->findAll();
        $data = [];
        foreach ($events as $event) {
            $data[] = [
                "id" => $event->getId(),
                "title" => $event->getTitle(),
                "date_start" => $event->getDateStart()->format('Y-m-d H:i:s'),
                "date_end" => $event->getDateEnd()->format('Y-m-d H:i:s'),
                "description" => $event->getDescription(),
                "date_created" => $event->getDateCreated()->format('Y-m-d H:i:s') 
            ];
        }

        return $data;
    }

    public function create(
        string $title, 
        \DateTime $date_start, 
        \DateTime $date_end, 
        string $description
    )
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $title . $description . $now->format('Ymdims'));

        $event = new Event();

        $event->setId($uuid);
        $event->setTitle($title); 
        $event->setDateStart($date_start); 
        $event->setDateEnd($date_end);
        $event->setDescription($description);
        $event->setDateCreated($now);

        $this->eventRepository->save($event);

        return $event;

    }

    public function listOneBy($id)
    {
        $event = $this->eventRepository->findOneBy(['id' => $id]);
        if(!$event) {
            throw new EventNotFound('Event not found');
        }

        $data[] = [
            "id" => $event->getId(),
            "title" => $event->getTitle(),
            "date_start" => $event->getDateStart()->format('Y-m-d H:i:s'),
            "date_end" => $event->getDateEnd()->format('Y-m-d H:i:s'),
            "description" => $event->getDescription(),
            "date_created" => $event->getDateCreated()->format('Y-m-d H:i:s') 
        ];

        return $data;
    }

    public function edit(
        $id,
        string $title, 
        \DateTime $date_start, 
        \DateTime $date_end, 
        string $description
    )
    {
        $event = $this->eventRepository->find($id);
        if(!$event) {
            throw new EventNotFound('Event not found');
        }

        (!empty($title)) ? $event->setTitle($title) : ''; 
        (!empty($date_start)) ? $event->setDateStart($date_start) : ''; 
        (!empty($date_end)) ? $event->setDateEnd($date_end) : '';
        (!empty($description)) ? $event->setDescription($description) : '';

        $this->eventRepository->update($event);
    }

    public function delete($id)
    {
        $this->eventRepository->destroy($id);
    }
}