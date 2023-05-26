<?php
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
