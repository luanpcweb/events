<?php

namespace App\Repository;

use App\Entity\Talk;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exceptions\TalkNotFound;

/**
 * @method Talk|null find($id, $lockMode = null, $lockVersion = null)
 * @method Talk|null findOneBy(array $criteria, array $orderBy = null)
 * @method Talk[]    findAll()
 * @method Talk[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TalkRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Talk::class);
        $this->manager = $manager;
    }

    /**
     * @param Talk $talk
     */
    public function save(Talk $talk): void
    {
        $this->manager->persist($talk);
        $this->manager->flush();
    }

    /**
     * @param Talk $data
     */
    public function update(Talk $data): void
    {

        $this->manager->persist($data);
        $this->manager->flush();
    }

    /**
     * @param string $id
     * @throws TalkNotFound
     */
    public function destroy(string $id): void
    {
        $talk = $this->find($id);
        if(!$talk) {
            throw new TalkNotFound('Talk not found');
        }

        $this->manager->remove($talk);
        $this->manager->flush();
    }
}
