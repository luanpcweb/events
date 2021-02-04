<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SpeakerService;

/**
  * Class TalkController
  * @package App\Controller\Api
  * @Route("/api", name="talk_api")
  */
class SpeakerController extends AbstractController
{
    /**
     * @var SpeakerService
     */
    private $speakerService;

    public function __construct(SpeakerService $speakerService)
    {
        $this->speakerService = $speakerService;
    }
    /**
     * @Route("/speakers", name="speakers")
     */
    public function index(): Response
    {
        $speaks = $this->speakerService->listAll();
        return $this->json($speaks);
    }
}
