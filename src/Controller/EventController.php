<?php

namespace App\Controller;

use App\Exceptions\ErrorOnCreatingEvent;
use App\Exceptions\EventNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventService;

class EventController extends AbstractController
{
    /**
     * @var EventService
     */
    private $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * @Route("/", name="events")
     */
    public function index(): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $events = [];

        try {
            $events = $this->eventService->listAll();
        } catch (EventNotFound $e) {

        }

        return $this->render('events/index.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/add", name="add_event")
     */
    public function add(): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('events/add.html.twig');
    }


    /**
     * @Route("/event/create", name="create_event", methods={"POST"})
     */
    public function create(Request $request): Response
    {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $this->eventService->create(
                $request->get('title'),
                new \DateTime($request->get('date_start')),
                new \DateTime($request->get('date_end')),
                $request->get('description')
            );

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-success',
                'msg' => 'Created with success!'
            ]);
        } catch (ErrorOnCreatingEvent $e) {
            $msg = $e->getMessage();
        }

        return $this->render('boxMsg.html.twig', [
            'type' => 'alert-danger',
            'msg' => $msg
        ]);

    }

    /**
     * @Route("/event/view/{id}", name="view_event")
     */
    public function view($id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }
        $event = [];

        try {
            $event = $this->eventService->listOneBy($id);
        }  catch (EventNotFound $e) {

        }

        return $this->render('events/view.html.twig', [
            'event' => $event[0],
        ]);
    }

    /**
     * @Route("/event/update/{id}", name="update_event", methods={"POST"})
     */
    public function update(Request $request, $id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }

        try {
            $this->eventService->edit(
                $id,
                $request->get('title'),
                new \DateTime($request->get('date_start')),
                new \DateTime($request->get('date_end')),
                $request->get('description')
            );

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-success',
                'msg' => 'Edited with success!'
            ]);
        } catch(EventNotFound $e) {
            $msg = $e->getMessage();
        } catch (\Exception $e) {
            $msg = 'Failed Edit!';
        }

        return $this->render('boxMsg.html.twig', [
            'type' => 'alert-danger',
            'msg' => $msg
        ]);

    }

    /**
     * @Route("/event/delete/{id}", name="delete_event", methods={"DELETE"})
     */
    public function delete(Request $request, $id): Response
    {

        if (!$this->getUser() || !$id) {
            return $this->redirectToRoute('app_login');
        }

        try {

            $this->eventService->delete($id);

            return $this->render('redirect.html.twig', [
                'redirect' => '/',
            ]);

        } catch (\EventNotFound $e) {
            $msg = $e->getMessage();
        } catch (\Exception $e) {
            $msg = 'Failed delete!';
        }

        return $this->render('boxMsg.html.twig', [
            'type' => 'alert-danger',
            'msg' => $msg
        ]);


    }

}
