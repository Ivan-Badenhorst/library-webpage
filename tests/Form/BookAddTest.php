<?php
namespace App\Tests\Form;

use App\Form\BookAdd;
use Symfony\Component\Form\Test\TypeTestCase;

class BookAddTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'add_to_favorites' => 'Save',
        ];

        $form = $this->factory->create(BookAdd::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}

