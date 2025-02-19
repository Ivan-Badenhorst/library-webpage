<?php
/**
 * @fileoverview This test was written to test functions written in BookSearch form
 * Tests are mostly self explanatory, data is created and submitted and checked if its correct
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
namespace App\Tests\Form;

use App\Form\BookSearch;
use Symfony\Component\Form\Test\TypeTestCase;

class BookSearchTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'search_term' => 'Book Title',
            'search' => 'Search',
        ];

        $form = $this->factory->create(BookSearch::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}
