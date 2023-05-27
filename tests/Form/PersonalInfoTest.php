<?php
namespace App\Tests\Form;

use App\Form\PersonalInfo;
use Symfony\Component\Form\Test\TypeTestCase;

class PersonalInfoTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'displayName' => 'johndoe',
            'email' => 'johndoe@example.com',
            'bio' => 'Lorem ipsum dolor sit amet.',
            'street' => '123 Street',
            'postalCode' => '12345',
        ];

        $form = $this->factory->create(PersonalInfo::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}
