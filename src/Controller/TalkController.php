<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SpeakerService;
use App\Service\TalkService;
use App\Service\EventService;

class TalkController extends AbstractController
{
    /**
     * @var TalkService
     */
    private $talkService;

    private $speakerService;

    private $eventEvent;

    public function __construct(
        TalkService $talkService,
        SpeakerService $speakerService,
        EventService $eventEvent
    )
    {
        $this->talkService = $talkService;
        $this->speakerService = $speakerService;
        $this->eventEvent = $eventEvent;
    }

    /**
     * @Route("/talks", name="talks")
     */
    public function index(): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        return $this->render('talks/index.html.twig', [
            'talks' => $this->talkService->listAll(),
        ]);
    }

    /**
     * @Route("/talk/add", name="add_talk")
     */
    public function add(): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('talks/add.html.twig', [
            'speakers' => $this->speakerService->listAll(),
            'events' => $this->eventEvent->listAll()
        ]);
    }

    /**
     * @Route("/talk/create", name="create_talk", methods={"POST"})
     */
    public function create(Request $request): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $this->talkService->create(
                $request->get('title'),
                new \DateTime($request->get('date')),
                new \DateTime($request->get('hourStart')),
                new \DateTime($request->get('hourEnd')),
                $request->get('description'),
                $request->get('event_id'),
                $request->get('speaker_id')
            );

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-success',
                'msg' => 'Created with success!'
            ]);
        } catch (\Exception $e) {

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-danger',
                'msg' => 'Failed Create!'
            ]);

        }

    }

    /**
     * @Route("/talk/view/{id}", name="view_talk")
     */
    public function view($id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }

        $talk = $this->talkService->listOneBy($id);

        return $this->render('talks/view.html.twig', [
            'talk' => $talk[0],
            'speakers' => $this->speakerService->listAll(),
            'events' => $this->eventEvent->listAll()
        ]);
    }

    /**
     * @Route("/talk/update/{id}", name="update_talk", methods={"POST"})
     */
    public function update(Request $request, $id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }

        try {

            $this->talkService->edit(
                 $request->get('id'),
                 $request->get('title'),
                 new \DateTime($request->get('date')),
                 new \DateTime($request->get('hourStart')),
                 new \DateTime($request->get('hourEnd')),
                 $request->get('description'),
                 $request->get('event_id'),
                 $request->get('speaker_id')
            );

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-success',
                'msg' => 'Edited with success!'
            ]);

        } catch (\Exception $e) {

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-danger',
                'msg' => 'Failed Edit!'
            ]);

        }

    }

    /**
     * @Route("/talk/delete/{id}", name="delete_talk", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }

        try {

            $this->talkService->delete($id);

            return $this->render('redirect.html.twig', [
                'redirect' => '/talks',
            ]);

        } catch (\Exception $e) {

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-danger',
                'msg' => 'Failed delete!'
            ]);

        }

    }
}
