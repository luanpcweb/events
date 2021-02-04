<?php

namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Repository\TalkRepository;
use App\Repository\EventRepository;
use App\Repository\SpeakerRepository;
use App\Entity\Talk;
use App\Exceptions\ErrorOnCreatingTalk;
use App\Exceptions\ErrorOnEditingTalk;
use App\Exceptions\TalkNotFound;
use Exception;

/**
 * Class TalkService
 * @package App\Service
 */
class TalkService
{
    /**
     * @var TalkRepository
     */
    private $talkRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var SpeakerRepository
     */
    private $speakerRepository;

    /**
     * TalkService constructor.
     * @param TalkRepository $talkRepository
     * @param EventRepository $eventRepository
     * @param SpeakerRepository $speakerRepository
     */
    public function __construct(
        TalkRepository $talkRepository,
        EventRepository $eventRepository,
        SpeakerRepository $speakerRepository
    ) {
        $this->talkRepository = $talkRepository;
        $this->eventRepository = $eventRepository;
        $this->speakerRepository = $speakerRepository;
    }

    /**
     * @return array
     */
    public function listAll(): array
    {
        try {
            $talks = $this->talkRepository->findAll();
            $data = [];
            foreach ($talks as $talk) {
                $data[] = [
                    "id" => $talk->getId(),
                    "title" => $talk->getTitle(),
                    "date" => $talk->getDate()->format('Y-m-d'),
                    "hour_start" => $talk->getHourStart()->format('H:i:s'),
                    "hour_end" => $talk->getHourEnd()->format('H:i:s'),
                    "description" => $talk->getDescription(),
                    "event" => $talk->getEvent()->getTitle(),
                    "event_id" => $talk->getEvent()->getId(),
                    "speaker" => $talk->getSpeaker()->getName(),
                    "speaker_id" => $talk->getSpeaker()->getId(),
                ];
            }

            return $data;
        } catch (\Exception $e) {
            throw new TalkNotFound('TAlk not found and not showed');
        }
    }

    /**
     * @param string $title
     * @param \DateTime $date
     * @param \DateTime $hourStart
     * @param \DateTime $hourEnd
     * @param string $description
     * @param string $eventId
     * @param string $speakerId
     * @return Talk
     * @throws ErrorOnCreatingTalk
     */
    public function create(
        string $title,
        \DateTime $date,
        \DateTime $hourStart,
        \DateTime $hourEnd,
        string $description,
        string $eventId,
        string $speakerId
    ): Talk
    {
        if (empty($title)) {
            throw new ErrorOnCreatingTalk('Empty Title');
        }
        if (empty($description)) {
            throw new ErrorOnCreatingTalk('Empty Description');
        }
        if (empty($eventId)) {
            throw new ErrorOnCreatingTalk('Empty Event ID');
        }
        if (empty($speakerId)) {
            throw new ErrorOnCreatingTalk('Empty Speaker ID');
        }

        $event = $this->eventRepository->findOneBy(['id' => $eventId]);
        if (!$event) {
            throw new ErrorOnCreatingTalk('Event not found');
        }

        if (
            ($date->format('Y-m-d H:i:s') < $event->getDateStartString()) ||
            ($date->format('Y-m-d H:i:s') > $event->getDateEndString())
        ) {
            throw new ErrorOnCreatingTalk('Date outside the event period');
        }

        $speaker = $this->speakerRepository->findOneBy(['id' => $speakerId]);
        if (!$speaker) {
            throw new ErrorOnCreatingTalk('Speaker not found');
        }

        try {
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $title . $description . $now->format('Ymdims') . $eventId);

            $talk = new Talk();
            $talk->setId($uuid);
            $talk->setTitle($title);
            $talk->setDate($date);
            $talk->setHourStart($hourStart);
            $talk->setHourEnd($hourEnd);
            $talk->setDescription($description);
            $talk->setEvent($event);
            $talk->setSpeaker($speaker);

            $this->talkRepository->save($talk);

            return $talk;
        } catch (\Exception $e) {
            throw new ErrorOnCreatingTalk('Error in creating Talk');
        }
    }

    /**
     * @param string $id
     * @return array<Talk>
     * @throws TalkNotFound
     */
    public function listOneBy(string $id): array
    {
        $talk = $this->talkRepository->findOneBy(['id' => $id]);
        if (!$talk) {
            throw new TalkNotFound('Talk not found');
        }

        $data[] = [
            "id" => $talk->getId(),
            "title" => $talk->getTitle(),
            "date" => $talk->getDate()->format('Y-m-d'),
            "hour_start" => $talk->getHourStart()->format('H:i:s'),
            "hour_end" => $talk->getHourEnd()->format('H:i:s'),
            "description" => $talk->getDescription(),
            "event" => $talk->getEvent()->getTitle(),
            "event_id" => $talk->getEvent()->getId(),
            "speaker" => $talk->getSpeaker()->getName(),
            "speaker_id" => $talk->getSpeaker()->getId(),
        ];

        return $data;
    }

    /**
     * @param string $id
     * @param string $title
     * @param \DateTime $date
     * @param \DateTime $hourStart
     * @param \DateTime $hourEnd
     * @param string $description
     * @param string $eventId
     * @param string $speakerId
     * @throws ErrorOnEditingTalk
     * @throws TalkNotFound
     */
    public function edit(
        string $id,
        string $title,
        \DateTime $date,
        \DateTime $hourStart,
        \DateTime $hourEnd,
        string $description,
        string $eventId,
        string $speakerId
    ): void
    {
        $talk = $this->talkRepository->find($id);
        if (!$talk) {
            throw new TalkNotFound('Talk not found');
        }

        $event = $this->eventRepository->findOneBy(['id' => $eventId]);
        if (!$event) {
            throw new ErrorOnEditingTalk('Event not found');
        }

        if (
            ($date->format('Y-m-d H:i:s') < $event->getDateStartString()) ||
            ($date->format('Y-m-d H:i:s') > $event->getDateEndString())
        ) {
            throw new ErrorOnEditingTalk('Date outside the event period');
        }


        $speaker = $this->speakerRepository->findOneBy(['id' => $speakerId]);
        if (!$speaker) {
            throw new ErrorOnEditingTalk('Speaker not found');
        }

        $hourStart->setTime($hourStart->format('H'), $hourStart->format('i'), '00');
        $hourEnd->setTime($hourEnd->format('H'), $hourEnd->format('i'), '00');

        try {
            (!empty($title)) ? $talk->setTitle($title) : '';
            (!empty($date)) ? $talk->setDate($date) : '';
            (!empty($hourStart)) ? $talk->setHourStart($hourStart) : '';
            (!empty($hourEnd)) ? $talk->setHourEnd($hourEnd) : '';
            (!empty($description)) ? $talk->setDescription($description) : '';
            (!empty($event)) ? $talk->setEvent($event) : '';
            (!empty($speaker)) ? $talk->setSpeaker($speaker) : '';

            $this->talkRepository->update($talk);
        } catch (Exception $e) {
            throw new ErrorOnEditingTalk('Error in editing Talk');
        }
    }

    /**
     * @param string $id
     * @throws TalkNotFound
     */
    public function delete(string $id): void
    {
        $this->talkRepository->destroy($id);
    }
}
