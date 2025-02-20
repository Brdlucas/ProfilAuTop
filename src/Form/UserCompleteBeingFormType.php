<?php

namespace App\Form;

use App\Entity\User;
use App\Form\LanguageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserCompleteBeingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('licences', ChoiceType::class, [
            'choices' => [
                'Permis AM (cyclomoteur)' => 'AM',
                'Permis A1 (moto légère)' => 'A1',
                'Permis A2 (moto intermédiaire)' => 'A2',
                'Permis A (moto toutes cylindrées)' => 'A',
                'Permis B (voiture)' => 'B',
                'Permis B1 (quadricycles lourds à moteur)' => 'B1',
                'Permis BE (voiture avec remorque lourde)' => 'BE',
                'Permis C1 (petits camions)' => 'C1',
                'Permis C (camions)' => 'C',
                'Permis C1E (petits camions avec remorque)' => 'C1E',
                'Permis CE (camions avec remorque)' => 'CE',
                'Permis D1 (minibus)' => 'D1',
                'Permis D (autocars)' => 'D',
                'Permis D1E (minibus avec remorque)' => 'D1E',
                'Permis DE (autocars avec remorque)' => 'DE',
            ],
            'multiple' => true,
            'autocomplete' => true,
        ])
        ->add('languages', CollectionType::class, [
            'entry_type' => LanguageType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false, // Important pour CollectionType
            'prototype' => true, // Permet l'ajout dynamique de champs avec JS
        ])
        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
