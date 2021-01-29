<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventService;
use App\Exceptions\EventNotFound;

/**
  * Class EventController
  * @package App\Controller
  * @Route("/api", name="event_api")
  */
class EventController extends AbstractController
{
    private $eventService;
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }
    /**
     * @Route("/events", name="events")
     */
    public function index(): Response
    {
        
        $events = $this->eventService->listAll();
        return $this->json($events);
    }

    /**
     * @Route("/event", name="add_event", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $event = $this->eventService->create(
            $request->get('title'), 
            new \DateTime($request->get('date_start')), 
            new \DateTime($request->get('date_end')), 
            $request->get('description')
        );

        return $this->json(['msg' => 'Event created successfully', 'id' => $event->getId()], 201);
    }

    /**
     * @Route("/events/{id}", name="get_one_event", methods={"GET"})
     */
    public function listOneBy($id): Response
    {
        try {
            $events = $this->eventService->listOneBy($id);
            return $this->json($events);
        } catch (EventNotFound $e) {
            return $this->json(['msg' => 'Event not found'], 400);
        }
    }

    /**
    * @Route("/events/{id}", name="events_put", methods={"PUT"})
    */
    public function edit(Request $request, $id): Response
    {
        
        try {
            
            $request = $this->transformJsonBody($request);

            $events = $this->eventService->edit(
                $id,
                $request->get('title'), 
                new \DateTime($request->get('date_start')), 
                new \DateTime($request->get('date_end')), 
                $request->get('description')
            );
            
            return $this->json($events);

        } catch (EventNotFound $e) {
            return $this->json(['msg' => 'Event not found'], 400);
        }
    }

    /**
    * @Route("/events/{id}", name="events_delete", methods={"DELETE"})
    */
    public function delete($id): Response
    {
        try {
            
            $events = $this->eventService->delete($id);
            return $this->json($events);

        } catch (EventNotFound $e) {
            return $this->json(['msg' => 'Event not found'], 400);
        }
    }

    protected function transformJsonBody(
        \Symfony\Component\HttpFoundation\Request $request
    )
    {
        $data = json_decode($request->getContent(), true);
    
        if ($data === null) {
        return $request;
        }
    
        $request->request->replace($data);
    
        return $request;
    }
  
}
