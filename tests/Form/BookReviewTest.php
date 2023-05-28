<?php
/**
 * @fileoverview This test was written to test functions written in BookReview form
 * Tests are mostly self explanatory, data is created and submitted and checked if its correct
 * @version 1.0
 */

/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
namespace App\Tests\Form;

use App\Form\BookReview;
use Symfony\Component\Form\Test\TypeTestCase;

class BookReviewTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'view_reviews' => 'View Reviews',
        ];

        $form = $this->factory->create(BookReview::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}
