<?php

namespace App\Form;

use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminCarouselType extends AbstractType
{   
    private function getConfiguration($label, $placeholder, $options = []) {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
                ]
            ], $options);
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Donnez un titre à l'image"))
            ->add('content', TextType::class, $this->getConfiguration("Texte", "Saisissez un texte de l'image"))
            ->add('btn_title', TextType::class, $this->getConfiguration("Texte du bouton", "Saisissez un texte"))
            ->add('btn_url', UrlType::class, $this->getConfiguration("URL ciblée par le bouton", "Saisissez l'URL visée par le bouton"))
            ->add('illustration', UrlType::class, $this->getConfiguration("URL de l'image", "Saisissez l'URL de l'image"))
            ->add('submit', SubmitType::class, [
                'label' => "VALIDER LES MODIFICATIONS",
                'attr' => [
                    'class' => 'btn-success mt-3',
                    'style' => 'font-weight: bold',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Carousel::class,
        ]);
    }
}
