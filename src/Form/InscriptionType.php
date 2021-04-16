<?php

namespace App\Form;

use App\Entity\Inscription;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message',TextareaType::class, [
                'required' => false ,
                'attr' => [ 'placeholder' => 'Je voudrais poser des questions sur...'],
                ])
            ->add('formation', EntityType::class, array(
                'class' => 'App:Formation',
                'choice_label' => 'titre',
                'required' => true,
                'placeholder' => 'choisir une formation',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f');
                },
                'attr' => ['data-plugin' => 'select2', 'placeholder' => 'choisir une formation'],

            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Inscription::class,
        ]);
    }
}
