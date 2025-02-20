<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\Category;
use App\Entity\Experience;
use App\Entity\Formation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'p-5 w-full',
                    'placeholder' => 'Entrez le titre du CV'
                ],
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('introduction', TextareaType::class, [
                'attr' => [
                    'class' => 'p-5',
                    'placeholder' => 'Présentez-vous brièvement',
                    'rows' => 4
                ],
                'label' => 'Introduction',
                'required' => false,
            ])
            ->add('date_start', TextType::class, [
                'attr' => [
                    'class' => 'p-5 bg-red-500',
                    'placeholder' => 'Date de début'
                ],
                'label' => 'Date de début',
                'required' => false,
            ])
            ->add('date_end', TextType::class, [
                'attr' => [
                    'class' => '',
                    'placeholder' => 'Date de fin'

                ],
                'label' => 'Date de fin',
                'required' => false,
            ])
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'block max-h-60 overflow-y-auto rounded-md border-gray-300'
                ],
                'label' => 'Catégories',
                'required' => false,
            ])
            ->add('experiences', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => function ($entity) {
                    return $entity->getTitle() . ' - ' . $entity->getOrganization();
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Expériences',
                'required' => false,
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => function ($entity) {
                    return $entity->getTitle() . ' - ' . $entity->getOrganization();
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => ''
                ],
                'label' => 'Formations',
                'required' => false,
            ])
            ->add('link', TextType::class, [
                'attr' => [
                    'class' => 'p-5',
                    'placeholder' => 'Lien vers votre CV / Portfolio'
                ],
                'label' => 'Lien',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cv::class,

        ]);
    }
}