<?php

namespace App\Form;

use App\Entity\Subscription;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('born', null, [
                'widget' => 'single_text'
            ])
            ->add('phone')
            ->add('postal_code')
            ->add('city')
            ->add('languages')
            ->add('pois')
            ->add('licences')
            ->add('linkedin')
            ->add('portfolio_url')
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
