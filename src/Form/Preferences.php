<?php
/**
 * @fileoverview php class preferences form:  create form for profile page
 * @version 1.0.0
 */

/**
 * @author Thomas Deseure
 * @since 2023-05-26.
 */


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class Preferences extends AbstractType
{
    /**
     * build form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('pref1', CheckboxType::class, [
            'label' => 'Preference 1',
            'required' => false,
        ])
            ->add('pref2', CheckboxType::class, [
                'label' => 'Preference 2',
                'required' => false,
            ])
            ->add('pref3', CheckboxType::class, [
                'label' => 'Preference 3',
                'required' => false,
            ])
            ->add('save', SubmitType::class);
    }
}
