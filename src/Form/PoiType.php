<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Catégorie du centre d'intérêt",
                'required' => true,
                'attr' => ['placeholder' => "Catégorie du centre d'intérêt"]
            ])
            ->add('items', CollectionType::class, [
                'entry_type' => ItemPoiFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true, // Ajout dynamique avec JS
                'label' => "Éléments du centre d'intérêt"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Pas d'entité, on stocke en JSON
        ]);
    }
}
