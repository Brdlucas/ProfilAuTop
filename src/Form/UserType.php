<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [ 
                'label' => "Votre prénom",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'], 
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm', // Tailwind input style
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre prénom doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre prénom ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => "Votre nom de famille",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom de famille',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom de famille doit faire au moins {{ limit }} caractères',
                        'max' => 50,
                        'maxMessage' => 'Votre nom de famille ne peut pas dépasser {{ limit }} caractères',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => "Votre adresse e-mail",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer une adresse email",
                    ]),
                    new Email([
                        'message' => "L'adresse email {{ value }} n'est pas valide",
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => "Saisissez votre mot de passe pour mettre à jour votre profil",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'placeholder' => "Mot de passe",
                    'class' =>
                    'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez entrer un mot de passe",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' =>
                        "Votre mot de passe doit faire au moins {{ limit }} caractères",
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => "Numéro de téléphone",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
            ])
            ->add('postal_code', TextType::class, [
                'label' => "Code postal",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                ],
            ])
            ->add('licences', ChoiceType::class, [
                'choices' => [
                    'Pas de permis' => 'none',
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
            ->add('linkedin', TextType::class, [
                'label' => "Profil LinkedIn",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => 'https://www.linkedin.com/in/votre-profil',
                ],
            ])
            ->add('portfolio_url', TextType::class, [
                'label' => "URL du portfolio",
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700'],
                'attr' => [
                    'class' => 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm',
                    'placeholder' => 'https://www.votre-portfolio.com',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['class' => 'grid grid-cols-2 gap-4 align-baseline'],
        ]);
    }
}
