<?php

namespace App\Form;

use App\Entity\Messages;
use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('message', TextareaType::class,[
                'attr' => [
                    'class' => 'form-control'
                ]
            ])  
            ->add('recipient', EntityType::class, [
                "class" => User::class,
                "choice_label" => "email",
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
