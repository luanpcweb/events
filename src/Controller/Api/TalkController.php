<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TalkService;
use App\Exceptions\ErrorOnCreatingTalk;
use App\Exceptions\ErrorOnEditingTalk;
use App\Exceptions\TalkNotFound;

/**
  * Class TalkController
  * @package App\Controller\Api
  * @Route("/api", name="talk_api")
  */
class TalkController extends AbstractController
{
    /**
     * @var TalkService
     */
    private $talkService;

    public function __construct(TalkService $talkService)
    {
        $this->talkService = $talkService;
    }
    /**
     * @Route("/talks", name="talks")
     */
    public function index(): Response
    {
        $events = $this->talkService->listAll();
        return $this->json($events);
    }

     /**
     * @Route("/talk", name="add_talk", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        try {
            $request = $this->transformJsonBody($request);

            $talk = $this->talkService->create(
                $request->get('title'),
                new \DateTime($request->get('date')),
                new \DateTime($request->get('hour_start')),
                new \DateTime($request->get('hour_end')),
                $request->get('description'),
                $request->get('event_id'),
                $request->get('speaker_id')
            );

            return $this->json(['msg' => 'Talk created successfully', 'id' => $talk->getId()], 201);
        } catch (ErrorOnCreatingTalk $e) {
            return $this->json(['msg' => $e->getMessage()], 400);
        }
    }

    /**
     * @Route("/talks/{id}", name="get_one_talk", methods={"GET"})
     */
    public function listOneBy(string $id): Response
    {
        try {
            $talk = $this->talkService->listOneBy($id);
            return $this->json($talk);
        } catch (TalkNotFound $e) {
            return $this->json(['msg' => 'Talk not found'], 404);
        }
    }

    /**
    * @Route("/talks/{id}", name="talks_put", methods={"PUT"})
    */
    public function edit(Request $request, string $id): Response
    {
        try {
            
            $request = $this->transformJsonBody($request);
            $this->talkService->edit(
                $id,
                $request->get('title'),
                new \DateTime($request->get('date')),
                new \DateTime($request->get('hour_start')),
                new \DateTime($request->get('hour_end')),
                $request->get('description'),
                $request->get('event_id'),
                $request->get('speaker_id')
            );
            return $this->json(['msg' => 'Talk edited']);

        } catch (TalkNotFound $e) {
            return $this->json(['msg' => 'Talk not found'], 404);
        } catch (ErrorOnEditingTalk $e) {
            return $this->json(['msg' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return $this->json(['msg' => $e->getMessage()], 400);
        }

    }

    /**
    * @Route("/talks/{id}", name="tlaks_delete", methods={"DELETE"})
    */
    public function delete(string $id): Response
    {
        try {
            
            $this->talkService->delete($id);
            return $this->json(['msg' => 'Talk deleted']);

        } catch (TalkNotFound $e) {
            return $this->json(['msg' => 'Talk not found'], 404);
        }
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected function transformJsonBody(
        \Symfony\Component\HttpFoundation\Request $request
    ): Request
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }
}
