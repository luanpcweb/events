<?php

namespace App\Controller;

use App\Exceptions\ErrorOnCreatingUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;

class RegistrationController extends AbstractController
{

    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {

        return $this->render('registration/index.html.twig');
    }

}
