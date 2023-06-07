<?php

namespace App\Tests\Form;

use App\Form\login;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginFormTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'showPassword' => true,
            // Add any other fields if necessary
        ];

        $form = $this->factory->create(Login::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['email'], $form->get('email')->getData());
        $this->assertEquals($formData['password'], $form->get('password')->getData());
        $this->assertEquals($formData['showPassword'], $form->get('showPassword')->getData());
        // Add assertions for other form fields if necessary
        $this->assertNotNull($form->get('submit'));
    }
}

/*
class LoginTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = array(
            'email' => 'johndoe@example.com',
            'password' => 'password',
        );

        $form = $this->factory->create(login::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData, $form->getData());
        $this->assertInstanceOf('Symfony\Component\Form\FormInterface', $form);
    }


}*/
