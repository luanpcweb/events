<?php


namespace App\Tests\Unit\Service;

use App\Entity\User;
use App\Exceptions\ErrorOnCreatingUser;
use App\Repository\UserRepository;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserServiceTest extends TestCase
{
    private $userRepository;

    private $encoder;

    private $userService;

    public function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->encoder = $this->getMockBuilder(UserPasswordEncoderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->userService = new UserService(
            $this->userRepository,
            $this->encoder
        );
    }

    /**
     * @test
     */
    public function shouldPersistUserOnDatabase()
    {
        $username = 'test';
        $password = '123';
        $email = 'test@me.com';

        $this->encoder->method('encodePassword')
            ->willReturn('dasdqwe2432423412dsa');

        $this->userRepository->expects($this->once())->method('save');
        $this->userService->create(
            $username,
            $email,
            $password,
            $password
        );
    }

    /**
     * @test
     */
    public function shouldPersistAGenerateUuid()
    {
        $lambda = function (User $user) {
            return !empty($user->getId());
        };

        $username = 'test';
        $password = '123';
        $email = 'test@me.com';

        $this->encoder->method('encodePassword')
            ->willReturn('dasdqwe2432423412dsa');

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($this->callback($lambda));

        $this->userService->create(
            $username,
            $email,
            $password,
            $password
        );

    }

    /**
     * @test
     */
    public function shouldNotPersistUserWithoutUsername()
    {
        $username = '';
        $password = '123';
        $email = 'test@me.com';

        $this->encoder->method('encodePassword')
            ->willReturn('dasdqwe2432423412dsa');

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->expectException(ErrorOnCreatingUser::class);

        $this->userService->create(
            $username,
            $email,
            $password,
            $password
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistUserWithoutPassword()
    {
        $username = 'test';
        $password = '';
        $email = 'test@me.com';

        $this->encoder->method('encodePassword')
            ->willReturn('dasdqwe2432423412dsa');

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->expectException(ErrorOnCreatingUser::class);

        $this->userService->create(
            $username,
            $email,
            $password,
            $password
        );
    }

    /**
     * @test
     */
    public function shouldNotPersistUserWithoutEmail()
    {
        $username = 'test';
        $password = '123';
        $email = '';

        $this->encoder->method('encodePassword')
            ->willReturn('dasdqwe2432423412dsa');

        $this->userRepository->expects($this->never())
            ->method('save');

        $this->expectException(ErrorOnCreatingUser::class);

        $this->userService->create(
            $username,
            $email,
            $password,
            $password
        );
    }

}