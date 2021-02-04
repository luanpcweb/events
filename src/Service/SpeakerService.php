<?php

namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Repository\TalkRepository;
use App\Repository\EventRepository;
use App\Repository\SpeakerRepository;


/**
 * Class TalkService
 * @package App\Service
 */
class SpeakerService
{

    /**
     * @var SpeakerRepository
     */
    private $speakerRepository;

    /**
     * SpeakerService constructor.
     * @param SpeakerRepository $speakerRepository
     */
    public function __construct(
        SpeakerRepository $speakerRepository
    ) {
        $this->speakerRepository = $speakerRepository;
    }

    /**
     * @return array
     */
    public function listAll(): array
    {
        $speakers = $this->speakerRepository->findAll();
        $data = [];
        foreach ($speakers as $speaker) {
            $data[] = [
                "id" => $speaker->getId(),
                "name" => $speaker->getName(),
                "city" => $speaker->getCity(),
            ];
        }

        return $data;
    }

}
