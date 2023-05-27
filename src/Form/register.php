<?php
/**
 * @fileoverview php class login form:  create form for login page
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-25.
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class register extends AbstractType
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
            ->add('email', EmailType::class)
            ->add("password", PasswordType::class)
            ->add('showPassword', CheckboxType::class, [
                'label' => 'Show Password',
                'required' => false,
            ])
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('displayname', TextType::class)
            ->add('DateOfBirth', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                                                ])
            ->add('street', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('city', TextType::class)
            ->add('profilePicture',FileType::class, [
                'label' => 'Profile picture Max size 2MB (optional)',
                'required' => false,
            ])
            ->add('submit', SubmitType::class);
        }
}