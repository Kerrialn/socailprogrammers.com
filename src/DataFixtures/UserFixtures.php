<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\IdenticonService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Generator $factory;

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private IdenticonService $identiconService
    )
    {
        $this->factory = Factory::create();
    }

    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setName($this->factory->name );
        $user->setEmail($this->factory->email);
        $password = $this->passwordHasher->hashPassword($user, '12345678');
        $user->setPassword($password);
        $user->setAvatar($this->identiconService->createIdenticon($user));
        $user->setTimezone($this->factory->timezone);


        $manager->persist($user);
        $manager->flush();
    }
}
