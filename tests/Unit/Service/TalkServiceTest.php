<?php


namespace App\Tests\Unit\Service;

use App\Entity\Event;
use App\Entity\Speaker;
use App\Entity\Talk;
use App\Exceptions\ErrorOnCreatingTalk;
use App\Exceptions\ErrorOnCreatingUser;
use App\Repository\TalkRepository;
use App\Repository\SpeakerRepository;
use App\Repository\EventRepository;
use App\Service\TalkService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TalkServiceTest extends TestCase
{
    private $talkRepository;

    private $eventRepository;

    private $speakerRepository;

    private $talkService;

    public function setUp(): void
    {
        $this->talkRepository = $this->getMockBuilder(TalkRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->speakerRepository = $this->getMockBuilder(SpeakerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->talkService = new TalkService(
            $this->talkRepository,
            $this->eventRepository,
            $this->speakerRepository
        );
    }

    /**
     * @test
     */
    public function shouldPersistTalkOnDatabase()
    {
        $title = 'Title';
        $date = new \DateTime('2021-11-09');
        $hourStart = new \DateTime('12:00:00');
        $hourEnd = new \DateTime('15:00:00');
        $description = 'Description';
        $eventId = '31231231231s';
        $speakerId = '423wedwqee23';

        $speaker = new Speaker();

        $event = new Event();
        $event->setDateStart(new \DateTime('2021-11-09'));
        $event->setDateEnd(new \DateTime('2021-11-19'));

        $this->eventRepository->method('findOneBy')
            ->willReturn($event);

        $this->speakerRepository->method('findOneBy')
            ->willReturn($speaker);

        $this->talkRepository->expects($this->once())->method('save');
        $this->talkService->create(
            $title,
            $date,
            $hourStart,
            $hourEnd,
            $description,
            $eventId,
            $speakerId
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistTalkWithoutTitle()
    {
        $title = '';
        $date = new \DateTime('2021-11-09');
        $hourStart = new \DateTime('12:00:00');
        $hourEnd = new \DateTime('15:00:00');
        $description = 'Description';
        $eventId = '31231231231s';
        $speakerId = '423wedwqee23';

        $event = new Event();
        $event->setDateStart(new \DateTime('2021-11-09'));
        $event->setDateEnd(new \DateTime('2021-11-19'));

        $speaker = new Speaker();

        $this->eventRepository->method('findOneBy')
            ->willReturn($event);

        $this->speakerRepository->method('findOneBy')
            ->willReturn($speaker);

        $this->expectException(ErrorOnCreatingTalk::class);

        $this->talkRepository->expects($this->never())
            ->method('save');

        $this->talkService->create(
            $title,
            $date,
            $hourStart,
            $hourEnd,
            $description,
            $eventId,
            $speakerId
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistTalkWithoutDescription()
    {
        $title = 'title';
        $date = new \DateTime('2021-11-09');
        $hourStart = new \DateTime('12:00:00');
        $hourEnd = new \DateTime('15:00:00');
        $description = '';
        $eventId = '31231231231s';
        $speakerId = '423wedwqee23';

        $event = new Event();
        $event->setDateStart(new \DateTime('2021-11-09'));
        $event->setDateEnd(new \DateTime('2021-11-19'));

        $speaker = new Speaker();

        $this->eventRepository->method('findOneBy')
            ->willReturn($event);

        $this->speakerRepository->method('findOneBy')
            ->willReturn($speaker);

        $this->expectException(ErrorOnCreatingTalk::class);

        $this->talkRepository->expects($this->never())
            ->method('save');

        $this->talkService->create(
            $title,
            $date,
            $hourStart,
            $hourEnd,
            $description,
            $eventId,
            $speakerId
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistTalkWithoutEventId()
    {
        $title = 'Title';
        $date = new \DateTime('2021-11-09');
        $hourStart = new \DateTime('12:00:00');
        $hourEnd = new \DateTime('15:00:00');
        $description = 'Description';
        $eventId = '';
        $speakerId = '423wedwqee23';

        $event = new Event();
        $event->setDateStart(new \DateTime('2021-11-09'));
        $event->setDateEnd(new \DateTime('2021-11-19'));

        $speaker = new Speaker();

        $this->eventRepository->method('findOneBy')
            ->willReturn($event);

        $this->speakerRepository->method('findOneBy')
            ->willReturn($speaker);

        $this->expectException(ErrorOnCreatingTalk::class);

        $this->talkRepository->expects($this->never())
            ->method('save');

        $this->talkService->create(
            $title,
            $date,
            $hourStart,
            $hourEnd,
            $description,
            $eventId,
            $speakerId
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistTalkWithoutSpeakerId()
    {
        $title = 'Title';
        $date = new \DateTime('2021-11-09');
        $hourStart = new \DateTime('12:00:00');
        $hourEnd = new \DateTime('15:00:00');
        $description = 'Description';
        $eventId = '31231231231s';
        $speakerId = '';

        $event = new Event();
        $event->setDateStart(new \DateTime('2021-11-09'));
        $event->setDateEnd(new \DateTime('2021-11-19'));

        $speaker = new Speaker();

        $this->eventRepository->method('findOneBy')
            ->willReturn($event);

        $this->speakerRepository->method('findOneBy')
            ->willReturn($speaker);

        $this->expectException(ErrorOnCreatingTalk::class);

        $this->talkRepository->expects($this->never())
            ->method('save');

        $this->talkService->create(
            $title,
            $date,
            $hourStart,
            $hourEnd,
            $description,
            $eventId,
            $speakerId
        );
    }

}