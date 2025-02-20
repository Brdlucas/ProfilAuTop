<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_start', TextType::class, [
                'label' => 'Date de début',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('date_end', TextType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]

            ])
            ->add('organization', TextType::class, [
                'label' => 'organisation',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Code postal',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    ),
                    new Length(
                        min: 5,
                        max: 5,
                        minMessage: "Le code postal doit faire au moins {{ limit }} caractères",
                        maxMessage: "Le code postal ne peut pas dépasser {{ limit }} caractères"
                    ),
                    new Regex(
                        pattern: "/^[0-9]{5}$/",
                        message: "Le code postal doit être composé de chiffres uniquement"
                    )
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'ville',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'pays',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1']
            ])
            ->add('level', TextType::class, [
                'label' => 'Niveau',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('is_graduated', CheckboxType::class, [
                'label' => 'Diplômé',
                'label_attr' => ['class' => 'block text-8xl'],
                'required' => false,
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
            ])
            ->add('degree', TextType::class, [
                'label' => 'Nom du Diplôme',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1']
            ])
            ->add('skills', EntityType::class, [
                'label' => "Compétences",
                'label_attr' => ['class' => 'w-full bg-red-800'],
                'attr' => ['class' => 'custom-wrapper'],
                'class' => Skill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}