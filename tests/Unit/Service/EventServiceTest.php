<?php


namespace App\Tests\Unit\Service;

use App\Entity\Event;
use App\Exceptions\ErrorOnCreatingEvent;
use App\Exceptions\ErrorOnCreatingTalk;
use App\Repository\EventRepository;
use App\Service\EventService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EventServiceTest extends TestCase
{
    private $eventRepository;

    private $eventService;

    public function setUp(): void
    {

        $this->eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventService = new EventService(
            $this->eventRepository
        );
    }

    /**
     * @test
     */
    public function shouldPersistEventOnDatabase()
    {
        $title = 'Title';
        $dateStart = new \DateTime();
        $dateEnd = new \DateTime();
        $description = 'description';


        $this->eventRepository->expects($this->once())->method('save');
        $this->eventService->create(
            $title,
            $dateStart,
            $dateEnd,
            $description
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistEventWithoutTitle()
    {

        $title = '';
        $dateStart = new \DateTime();
        $dateEnd = new \DateTime();
        $description = 'description';

        $this->expectException(ErrorOnCreatingEvent::class);

        $this->eventRepository->expects($this->never())->method('save');
        $this->eventService->create(
            $title,
            $dateStart,
            $dateEnd,
            $description
        );

    }

    /**
     * @test
     */
    public function shouldNotPersistEventWithoutDescription()
    {
        $title = 'Title';
        $dateStart = new \DateTime();
        $dateEnd = new \DateTime();
        $description = '';

        $this->expectException(ErrorOnCreatingEvent::class);

        $this->eventRepository->expects($this->never())->method('save');
        $this->eventService->create(
            $title,
            $dateStart,
            $dateEnd,
            $description
        );

    }

}