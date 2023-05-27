<?php
namespace App\Tests\Form;

use App\Form\SecurityPrivacy;
use Symfony\Component\Form\Test\TypeTestCase;

class SecurityPrivacyTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'password' => 'newpassword',
            'showPassword' => true,
        ];

        $form = $this->factory->create(SecurityPrivacy::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}
