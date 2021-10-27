<?php

namespace App\Form;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\City;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('address',TextType::class)
            //->add('slug')
            ->add('description', TextareaType::class)
            //->add('created_at')
            //->add('active')
            ->add('beginDate')
            ->add('endDate')
            ->add('participantsMax', NumberType::class)
            //->add('participantsNumber', NumberType::class)
            ->add('prerequisite', TextareaType::class)
            ->add('profile', TextareaType::class)
            //->add('user')
            ->add('category',EntityType::class,[
                'class' => Category::class
            ])
            ->add('city',EntityType::class,[
                'class' => City::class
            ])
            ->add('Soumettre',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);
    }
}
