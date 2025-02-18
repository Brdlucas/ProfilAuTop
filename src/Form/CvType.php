<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Cv;
use App\Entity\Experience;
use App\Entity\Formation;
use App\Entity\Offer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CvType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('introduction')
            ->add('date_start')
            ->add('date_end')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('experiences', EntityType::class, [
                'class' => Experience::class,
'choice_label' => 'id',
'multiple' => true,
            ])
            ->add('formations', EntityType::class, [
                'class' => Formation::class,
'choice_label' => 'id',
'multiple' => true,
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
