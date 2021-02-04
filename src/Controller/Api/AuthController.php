<?php

namespace App\Controller\Api;

use App\Exceptions\ErrorOnCreatingUser;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\UserService;

class AuthController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request): Response
    {
        try {

            $typeResponse = 'json';
            if ($request->headers->get('Content-Type') !== 'application/json') {
                $typeResponse = 'web';
            }
            $request = $this->transformJsonBody($request);

            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            $confirmPassword = $password;

            $this->userService->create(
                $username,
                $email,
                $password,
                $confirmPassword
            );

            return $this->registerResponse($typeResponse, 'success', 'User created successfully', '/');

        } catch (ErrorOnCreatingUser $e) {
            return $this->registerResponse($typeResponse, 'fail', $e->getMessage(), null);
        } catch (\Exception $e) {
            return $this->registerResponse($typeResponse, 'fail', $e->getMessage(), null);
        }
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return Response
     */
    public function login(
        UserInterface $user,
        JWTTokenManagerInterface $JWTManager
    ): Response
    {
        return $this->json(['token' => $JWTManager->create($user)]);
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

    private function registerResponse($typeResponse, $status, $msg, $redirect)
    {
        if($typeResponse == 'web') {
            if ($status == 'success') {
                return $this->render('redirect.html.twig', [
                    'redirect' => $redirect
                ]);
            }

            return $this->render('boxMsg.html.twig', [
                'type' => 'alert-danger',
                'msg' => $msg
            ]);
        }

        if ($status == 'success') {
            return $this->json(['msg' => $msg]);
        }

        return $this->json(['msg' => $msg], 400);
    }
}
