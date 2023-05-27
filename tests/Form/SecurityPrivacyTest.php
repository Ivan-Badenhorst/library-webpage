<?php
namespace App\Tests\Form;

use App\Form\SecurityPrivacy;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * This test was written to test functions written in SecurityPrivacy form
 * Tests are mostly self explanatory, data is created and submitted and checked if its correct
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
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
