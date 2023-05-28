<?php
/**
 * @fileoverview Form for add/remove to/from reading list functionality
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

class BookAdd extends AbstractType
{
    /**
     * Form used for add or remove from favorites functionality on the book-info page
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('add_to_favorites', SubmitType::class);
    }
}