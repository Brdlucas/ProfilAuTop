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

class LanguagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('languages', CollectionType::class, [
            'entry_type' => LanguageType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false, // Important pour CollectionType
            'prototype' => true, // Permet l'ajout dynamique de champs avec JS
        ])
        ->add('submit', SubmitType::class, [
            'label' => 'Enregistrer',
            'attr' => [
                'class' => 'bg-sky-500 hover:bg-sky-400 text-white font-bold py-2 px-4 rounded',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
