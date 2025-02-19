<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\User;
use App\Entity\Skill;
use App\Entity\Experience;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_start', TextType::class, [
                'label' => 'Date de début',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
            ])
            ->add('date_end', TextType::class, [
                'label' => 'Date de fin',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],

            ])
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],

            ])
            ->add('organization', TextType::class, [
                'label' => 'organisation',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
            ])
            ->add('postal_code', TextType::class, [
                'label' => 'Code postal',
                'label_attr' => ['class' => 'block'],

                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
            ])
            ->add('city', TextType::class, [
                'label' => 'ville',
                'label_attr' => ['class' => 'block'],

                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
            ])
            ->add('country', CountryType::class, [
                'label' => 'pays',
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'constraints' => [
                    new NotBlank(
                        message: "Ce champs est obligatoire"
                    )
                ]
            ])
            ->add('skills', EntityType::class, [
                'label' => "Compétences",
                'label_attr' => ['class' => 'block'],
                'attr' => ['class' => 'border border-gray-300 rounded-md w-full p-1'],
                'class' => Skill::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}
