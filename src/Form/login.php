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
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class login extends AbstractType
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
            ->add('submit', SubmitType::class);
    }
}