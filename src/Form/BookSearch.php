<?php
/**
 * @fileoverview Form for search functionality
 * @version 1.0
 */

/**
 * @author Ivan Badenhorst
 * @since 2023-04-20.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class BookSearch extends AbstractType
{
    /**
     * Form used for search functionality on the home-page
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search_term', TextType::class)
            ->add('search', SubmitType::class);
    }
}