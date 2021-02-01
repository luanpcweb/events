<?php

namespace App\DataFixtures;

use App\Entity\Speaker;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        $name = $faker->name();
        $email = $faker->email();

        $speaker = new Speaker();
        $speaker->setId('0218d78e-6438-11eb-aa73-0242ac180002');
        $speaker->setName($name);
        $speaker->setCity('São Paulo');
        $manager->persist($speaker);

        $username = 'test';
        $email = 'test@me.com';
        $password = '12345678';

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $uuid = Uuid::uuid5(
            Uuid::NAMESPACE_URL,
            $username . $email . $now->format('Ymdims')
        );

        $user = new User($username);
        $user->setId($uuid);
        $user->setEmail($email);
        $user->setPassword($this->encoder->encodePassword($user, $password));
        $user->setUsername($username);
        $manager->persist($user);

        $manager->flush();
    }
}
