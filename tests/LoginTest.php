<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class LoginTest extends WebTestCase
{

    public function testLogin(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $userRepository = $container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('jane.doe@unittest.com');
        $client->loginUser($testUser);

        $client->request('GET', '/dashboard');
        $this->assertResponseIsSuccessful();
    }

    public function testLoginInvalidCredentials(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, '/login', [
            'email' => 'jane.doe@unittest.com',
            'password' => 'invalidpassword'
        ]);

        $this->assertResponseRedirects('/login');
    }
}
