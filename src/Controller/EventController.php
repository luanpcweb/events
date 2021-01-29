<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\EventService;

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
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EventController.php',
        ]);
    }

    /**
     * @Route("/event", name="add_event", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $request = $this->transformJsonBody($request);

        $this->eventService->create(
            $request->get('title'), 
            $request->get('date_start'), 
            $request->get('date_end'), 
            $request->get('description')
        );

        return $this->json([
            'status' => 200,
            'msg' => 'Event created successfully',
        ]);
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
