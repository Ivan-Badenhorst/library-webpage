<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;


class SearchFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->add('isAttending', ChoiceType::class, [
//            'choices'  => [
//                'Maybe' => null,
//                'Yes' => true,
//                'No' => false,
//            ],
//        ]);
//    }


//        foreach ($options as $label) {
//            $builder->add('public', CheckboxType::class, [
//                'label' => $label,
//                'required' => false,
//            ]);
//        }

//        foreach ($options as $genre) {
//            $builder->add('public', CheckboxType::class, [
//                'label' => $genre,
//                'required' => false,
//            ]);
//        }
    }
}

