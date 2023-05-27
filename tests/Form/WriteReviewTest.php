<?php
namespace App\Tests\Form;

use App\Form\WriteReview;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * This test was written to test functions written in WriteReview form
 * Tests are mostly self explanatory, data is created and submitted and checked if its correct
 */
/**
 * @author Wout Houpeline
 * @since 2023-05-27
 */
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
