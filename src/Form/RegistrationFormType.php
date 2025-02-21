<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre adresse e-mail',
                'label_attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-sky-900 nodark:text-white',
                ],
                'attr' => [
                    'placeholder' => 'profilautop@example.com',
                    'class' => 'bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 nodark:bg-sky-700 nodark:border-sky-600 nodark:placeholder-sky-400 nodark:text-white nodark:focus:ring-sky-500 nodark:focus:border-sky-500',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir une adresse e-mail valide',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'mapped' => false,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-sky-900 nodark:text-white'],
                    'attr' => [
                        'placeholder' => '••••••••',
                        'class' => 'bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 nodark:bg-sky-700 nodark:border-sky-600 nodark:placeholder-sky-400 nodark:text-white nodark:focus:ring-sky-500 nodark:focus:border-sky-500']
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'label_attr' => ['class' => 'block mb-2 text-sm font-medium text-sky-900 nodark:text-white'],
                    'attr' => [
                        'placeholder' => '••••••••',
                        'class' => 'bg-sky-50 border border-sky-300 text-sky-900 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block w-full p-2.5 nodark:bg-sky-700 nodark:border-sky-600 nodark:placeholder-sky-400 nodark:text-white nodark:focus:ring-sky-500 nodark:focus:border-sky-500']
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('isMajor', CheckboxType::class, [
                'label' => "Vous confirmez que vous êtes majeur",
                'label_attr' => ['class' => 'ml-2 text-sm font-medium text-sky-900 nodark:text-sky-300'],
                'attr' => ['class' => 'w-4 h-4 text-sky-600 bg-sky-100 border-sky-300 rounded focus:ring-sky-500 nodark:focus:ring-sky-600 nodark:ring-offset-sky-800 focus:ring-2 nodark:bg-sky-700 nodark:border-sky-600'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez être majeur pour vous inscrire',
                    ]),
                ],
            ])
            ->add('isTerms', CheckboxType::class, [
                'label' => "J'accepte les conditions d'utilisation",
                'label_attr' => ['class' => 'ml-2 text-sm font-medium text-sky-900 nodark:text-sky-300'],
                'attr' => ['class' => 'w-4 h-4 text-sky-600 bg-sky-100 border-sky-300 rounded focus:ring-sky-500 nodark:focus:ring-sky-600 nodark:ring-offset-sky-800 focus:ring-2 nodark:bg-sky-700 nodark:border-sky-600'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les CGU pour vous inscrire',
                    ]),
                ],
            ])
            ->add('isGpdr', CheckboxType::class, [
                'label' => "J'accepte la politique RGPD de Profil au Top",
                'label_attr' => ['class' => 'ml-2 text-sm font-medium text-sky-900 nodark:text-sky-300'],
                'attr' => ['class' => 'w-4 h-4 text-sky-600 bg-sky-100 border-sky-300 rounded focus:ring-sky-500 nodark:focus:ring-sky-600 nodark:ring-offset-sky-800 focus:ring-2 nodark:bg-sky-700 nodark:border-sky-600'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter notre politique RGPD pour vous inscrire',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => "M'inscrire",
                'attr' => [
                    'class' => 'w-full bg-sky-500 text-white py-3 rounded-lg font-semibold hover:bg-sky-700 focus:ring-4 focus:ring-sky-600 focus:ring-opacity-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed',
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
