<?php
namespace App\Tests\Form;

use App\Form\WriteReview;
use Symfony\Component\Form\Test\TypeTestCase;

class WriteReviewTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'score' => '8',
            'comment' => 'Great book!',
        ];

        $form = $this->factory->create(WriteReview::class);

        $form->submit($formData);

        $this->assertTrue($form->isSubmitted());
        $this->assertTrue($form->isValid());
    }
}
