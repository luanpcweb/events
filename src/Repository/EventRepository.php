<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exceptions\EventNotFound;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Event::class);
        $this->manager = $manager;
    }

    /**
     * @param Event $event
     */
    public function save(Event $event): void
    {
        $this->manager->persist($event);
        $this->manager->flush();
    }

    /**
     * @param Event $data
     */
    public function update(Event $data): void
    {

        $this->manager->persist($data);
        $this->manager->flush();
    }

    /**
     * @param string $id
     * @throws EventNotFound
     */
    public function destroy(string $id): void
    {
        $event = $this->find($id);
        if(!$event) {
            throw new EventNotFound('Event not found');
        }

        $this->manager->remove($event);
        $this->manager->flush();
    }
    
}
