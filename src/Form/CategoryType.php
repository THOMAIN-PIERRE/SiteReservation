<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfiguration("Intitulé", "Saisissez un nom de catégorie"))
            ->add('description', TextType::class, $this->getConfiguration("Description", "Court descriptif de la catégorie"))
            ->add('color', TextType::class, $this->getConfiguration("Couleur", "Couleur de la catégorie"))
            ->add('submit', SubmitType::class, [
                'label' => "VALIDER",
                'attr' => [
                    'class' => 'btn-success mt-2',
                    'style' => 'font-weight: bold',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
