<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NextPageControl extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('previous', ButtonType::class, [
                'label' => '< Previous Page',
                'attr' => ['class' => 'btn btn-primary'],
            ])
            ->add('current', TextType::class, [
                'label' => 'Current Page',
                'disabled' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('next', ButtonType::class, [
                'label' => 'Next Page >',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
