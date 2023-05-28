<?php
/**
 * @fileoverview Form for viewing reviews functionality
 * @version 1.0
 */

/**
 * @author Aymeric Baume
 * @since 2023-05-25.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookReview extends AbstractType
{
    /**
     * Form used for viewing reviews in the book-info page
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('view_reviews', SubmitType::class);
    }
}