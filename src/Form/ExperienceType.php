<?php

namespace App\Form;

use App\Entity\Cv;
use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_start')
            ->add('date_end')
            ->add('title')
            ->add('organization')
            ->add('description')
            ->add('postal_code')
            ->add('city')
            ->add('country')
            ->add('skills', EntityType::class, [
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
