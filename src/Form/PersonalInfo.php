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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
            ->add('firstName', TextType::class, [
                'required' => false
            ])
            ->add("lastName", TextType::class, [
                'required' => false
            ])
            ->add('dateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => false
            ])
            ->add('displayName', TextType::class, [
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('bio', TextType::class, [
                'required' => false
            ])
            ->add('street', TextType::class, [
                'required' => false
            ])
            ->add('postalCode', TextType::class, [
                'required' => false
            ])
            ->add('city', TextType::class, [
                'required' => false
            ])
            ->add('profilePicture',FileType::class, [
            'label' => 'Profile picture Max size 2MB (optional)',
            'required' => false
            ])
            ->add('save', SubmitType::class);

    }
}
