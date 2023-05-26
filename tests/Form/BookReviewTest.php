<?php
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
