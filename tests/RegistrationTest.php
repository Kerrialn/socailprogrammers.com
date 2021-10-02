<?php

namespace App\Tests;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class RegistrationTest extends TypeTestCase
{

    public function testRegistration(): void
    {

        $formData = [
            'name' => 'John Doe',
            'email' => 'john.doe@testing.com',
            'password' => '12345678',
            'avatar' => 'test-image',
            'timezone' => 'Europe/Prague',
        ];

        $model = new User();
        $form = $this->factory->create(RegistrationFormType::class, $model);
        $form->get('agreeTerms')->setData(true);

        $expected = new User();
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
