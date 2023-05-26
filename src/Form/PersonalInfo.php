<?php
/**
 * @fileoverview php class personalInfo form:  create form for profile page
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-26.
 */


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PersonalInfo extends AbstractType
{
    /**
     * build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add("lastName", TextType::class)
            ->add('displayName', TextType::class)
            ->add('email', EmailType::class)
            ->add('bio', TextType::class)
            ->add('street', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('save', SubmitType::class);
    }
}
