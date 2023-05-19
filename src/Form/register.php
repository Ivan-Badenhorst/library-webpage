<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class register extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add("password", PasswordType::class)
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
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'maxSize' => '2M',
                'mimeTypes' => [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                ],
                'mimeTypesMessage' => 'Please upload a valid image file',
                ])
            ],
            ])
            ->add('submit', SubmitType::class);
        }
}