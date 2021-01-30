<?php
namespace App\Controller\Api;


use App\Entity\User;
use App\Exceptions\ErrorOnCreatingUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\UserService;

class AuthController extends ApiController
{

    private $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        
        try {
            $request = $this->transformJsonBody($request);

            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            $user = $this->userService->create(
                $username,
                $password,
                $email
            );

            return $this->json(['msg' => 'User created successfully']);

        } catch(ErrorOnCreatingUser $e) {
            return $this->json(['msg' => $e->getMessage()], 400);
        }
    }

    /**
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function login(
        UserInterface $user, 
        JWTTokenManagerInterface $JWTManager
    )
    {
        return new JsonResponse(['token' => $JWTManager->create($user)]);
    }

}