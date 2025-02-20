<?php

namespace App\Form;

use App\Entity\Poi;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Subscription;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPoiFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une catégorie',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.type = :type')
                        ->setParameter('type', 'loisir')
                        ->orderBy('c.name', 'ASC');
                },
                'mapped' => false,
            ])
            ->add('pois', PoiAutocompleteField::class, [
                'class' => Poi::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
