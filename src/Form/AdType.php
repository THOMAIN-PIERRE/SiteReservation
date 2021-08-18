<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Entity\Category;
use App\Form\ApplicationType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, $this->getConfiguration("Titre", "Titre de l'annonce"))
        // ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Adresse web de votre annonce (automatique)", ['required' => false]))
        ->add('city', TextType::class, $this->getConfiguration("Ville", "Dans quelle ville se trouve le bien", ['required' => false]))
        ->add('price', MoneyType::class, $this->getConfiguration("Prix par nuit", "Prix demandé / nuit"))
        ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Nb de chambres"))
        ->add('traveler', IntegerType::class, $this->getConfiguration("Nombre de voyageurs", "Nb de voyageurs"))
        ->add('bed', IntegerType::class, $this->getConfiguration("Nombre de lits", "Nb de lits"))
        ->add('category', EntityType::class, [
            'label' => 'Catégorie de logement',
            'class' => Category::class,
            'choice_label' => 'name'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex : 10 Avenue des Champs-Élysées Paris, France',
                    'class' => 'form-control',
                    'type' => 'text',
                    ]
            ])
            ->add('zipCode', TextType::class, [
            'label' => 'Code postal',
            'required' => true,
            'attr' => [
                'placeholder' => 'Ex : 75008'
            ]
            ])
            ->add('tel', TelType::class, [
                'label' => 'Téléphone',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ex : 0601020304'
                ]
            ])    
            ->add('country', CountryType::class, [
                'preferred_choices' => ['FR'],
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Pays'
                ]
            ])
        ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentation succinte de votre bien"))
        // ->add('content', TextareaType::class, $this->getConfiguration("Description détaillée", "Description détaillée de votre annonce"))
        ->add('text', CKEditorType::class, $this->getConfiguration("Description détaillée du bien", "Donnez envie au lecteur de venir chez vous"))
        ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "Adresse d'une image de couverture pour votre bien"))
        ->add('images', CollectionType::class, ['entry_type' => ImageType::class, 'allow_add' => true, 'allow_delete' => true])
        ->add('latitude', HiddenType::class, [
            'attr' => [
                'label' => 'Latitude',
                'placeholder' => 'Latitude',
            ]

        ]) // Je laisse le framework décider du type de champs à utiliser pour cette donnée
        ->add('longitude', HiddenType::class, [
            'label' => 'Longitude',
            'attr' => [
                'placeholder' => 'Longitude',
            ]

        ]) // Je laisse le framework décider du type de champs à utiliser pour cette donnée
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
            'data_class' => Ad::class,
        ]);
    }
}
