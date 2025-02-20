<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\Category;
use App\Entity\Formation;
use App\Entity\SoftSkill;
use App\Entity\Experience;
use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

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
            ->add('softskills', EntityType::class, [
                'label' => 'Compétences personnelles',
                'class' => SoftSkill::class,
                'choice_label' => 'name',
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add('offer', EntityType::class, [
                'label' => 'Poster sur l\'Offre',
                'class' => Offer::class,
                'multiple' => false,
                'autocomplete' => true,
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