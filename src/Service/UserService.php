<?php 
namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Exceptions\ErrorOnCreatingUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $userRepository;
    private $encoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    public function create(
        string $username, 
        string $password, 
        string $email
    )
    {

        if (empty($username)) {
            throw new ErrorOnCreatingUser('Empty Username');
        }
        
        if (empty($password)) {
            throw new ErrorOnCreatingUser('Empty Password');
        }

        if (empty($email)) {
            throw new ErrorOnCreatingUser('Empty email');
        }

        try {

            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $username . $email . $now->format('Ymdims'));

            $user = new User($username);
            $user->setId($uuid);
            $user->setUsername($username); 
            $user->setPassword($this->encoder->encodePassword($user, $password)); 
            $user->setEmail($email);

            $this->userRepository->save($user);

            return $user;

        } catch(\Exception $e) {
            throw new ErrorOnCreatingUser('Error in creating User');
        }
    }

}