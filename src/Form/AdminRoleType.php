<?php

namespace App\Form;

use App\Entity\Role;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminRoleType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Intitulé du rôle", "Saisir l'intitulé du rôle"))
            ->add('description', TextType::class, $this->getConfiguration("Description du rôle", "Décrire les droits ouverts"))
            ->add('submit', SubmitType::class, [
                'label' => "VALIDER",
                'attr' => [
                    'class' => 'btn-success mt-2',
                    'style' => 'font-weight: bold',
                ]
            ])
            // ->add('userblog')
            // ->add('title')
            // ->add('description')
            
            // ->add('users')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
