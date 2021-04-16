<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nom',null, ['required' => true,'trim'=>true ])
            ->add('prenom',null, ['required' => true,'trim'=>true ])
            ->add('telephone',null, ['required' => true,'trim'=>true ])
            ->add('adresse',null, ['required' => true,'trim'=>true ])
           // ->add('dateNaissance',null, ['required' => true,'trim'=>true ])
          //  ->add('enable')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
