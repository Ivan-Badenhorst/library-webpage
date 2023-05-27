<?php
namespace App\Tests\Form;

use App\Form\register;
use Symfony\Component\Form\Test\TypeTestCase;

class RegisterTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'showPassword' => true,
            'name' => 'John',
            'surname' => 'Doe',
            'displayname' => 'johndoe',
            'DateOfBirth' => new \DateTime('1990-01-01'),
            'street' => '123 Main Street',
            'postalCode' => '12345',
            'city' => 'New York',
            'profilePicture' => null,
        ];

        $form = $this->factory->create(register::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
    }
}
