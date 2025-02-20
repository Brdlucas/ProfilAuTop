<?php

namespace App\Form;

use App\Entity\Poi;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserPoiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pois', EntityType::class, [
                'class' => Poi::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer mes centres d\'intérêt',
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
