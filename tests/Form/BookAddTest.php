<?php

/**
 * @fileoverview This test was written to test functions written in BookAdd form
 * Tests are mostly self explanatory, data is created and submitted and checked if its correct
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */

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

