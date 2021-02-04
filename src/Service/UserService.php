<?php

namespace App\Service;

use Ramsey\Uuid\Uuid;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Exceptions\ErrorOnCreatingUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserService
 * @package App\Service
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * Create User
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $confirmPassword
     * @return User
     * @throws ErrorOnCreatingUser
     */
    public function create(
        string $username,
        string $email,
        string $password,
        string $confirmPassword
    ) {
        if (empty($username)) {
            throw new ErrorOnCreatingUser('Empty Username');
        }
        if (empty($email)) {
            throw new ErrorOnCreatingUser('Empty email');
        }
        if (empty($password)) {
            throw new ErrorOnCreatingUser('Empty Password');
        }
        if ($confirmPassword !== $password) {
            throw new ErrorOnCreatingUser('Password not match');
        }

        try {
            $now = new \DateTime('now', new \DateTimeZone('UTC'));
            $uuid = Uuid::uuid5(
                Uuid::NAMESPACE_URL,
                $username . $email . $now->format('Ymdims')
            );

            $user = new User($username);
            $user->setId($uuid);
            $user->setUsername($username);
            $user->setPassword($this->encoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles(["ROLE_USER"]);

            $this->userRepository->save($user);

            return $user;
        } catch (\Exception $e) {
            throw new ErrorOnCreatingUser('Error in creating User');
        }
    }
}
