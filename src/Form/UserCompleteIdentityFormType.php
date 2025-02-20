<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserCompleteIdentityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('born', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('phone')
            ->add('postal_code')
            ->add('city')
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer mes informations',
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
