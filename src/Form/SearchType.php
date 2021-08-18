<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Category;
use App\Data\SearchData;;
use App\Entity\Categorie;
use App\Entity\Conditionnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SearchType extends AbstractType
{

    // Fonction buildForm de construction de formulaire (prend 2 paramètres un tableau d'options et l'interface de notre builder) 
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('city', TextType::class, [
                'label' => false,
                'label_attr' => [
                    'style' => 'color: white',
                ],
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ville de destination',
                    'class' => 'col-md-12 text-center m-auto',
                    // 'class' => 'col-md-12 align-items-middle'
                ]
            ])


            ->add('categories', EntityType::class, [
                'label' => false,
                'label_attr' => [
                    'style' => 'color: white',
                ],
                'required' => false,
                'class' => Category::class,
                // Pour afficher des checkbox
                'expanded' => true,
                'multiple' => true,
                'attr' => [
                    // 'class' => 'w-50 btn-block btn-info p-1',
                    // 'class' => 'w-100 btn-block p-1',
                    // 'class' => 'col-md-4 m-auto',
                    // 'class' => 'row d-flex justify-content-around',
                ]
            ])


            ->add('min', ChoiceType::class,[
                'choices' => [
                    '' => '',
                    '0' => '0',
                    '25' => '25',
                    '50' => '50',
                    '75' => '75',
                    '100' => '100',
                    '125' => '125',
                    '150' => '150',
                    '175' => '175',
                    '200' => '200',
                    '225' => '225',
                    '250' => '250',
                    '275' => '275',
                    '300' => '300',
                    '350' => '350',
                    '400' => '400',
                    '450' => '450',
                    '500' => '500',
                    '600' => '600',
                    '700' => '700',
                    '800' => '800',
                    '900' => '900',
                    '1000' => '1000',
                    '2000' => '2000',
                    '3000' => '3000',
                    '4000' => '4000',
                    '5000' => '5000',
            ],
                'multiple'  => false,   
                'expanded'  => false,
                'required' => false, 
                'label' => 'Min',
                'label_attr' => [
                    'style' => 'color: white',
                ],
                'attr' => [
                    // 'class' => 'w-50 btn-block btn-info p-1',
                    'class' => 'w-100 btn-block p-1',
                    'class' => 'col-md-12 text-center m-auto',
                ]
            ])

            ->add('max', ChoiceType::class,[
                'choices' => [
                '' => '',
                '0' => '0',
                '25' => '25',
                '50' => '50',
                '75' => '30',
                '100' => '100',
                '125' => '125',
                '150' => '150',
                '175' => '175',
                '200' => '200',
                '225' => '225',
                '250' => '250',
                '275' => '275',
                '300' => '300',
                '350' => '350',
                '400' => '400',
                '450' => '450',
                '500' => '500',
                '600' => '600',
                '700' => '700',
                '800' => '800',
                '900' => '900',
                '1000' => '1000',
                '2000' => '2000',
                '3000' => '3000',
                '4000' => '4000',
                '5000' => '5000',
            ],
                'multiple'  => false,   
                'expanded'  => false,
                'required' => false, 
                'label' => 'Max',
                'label_attr' => [
                    'style' => 'color: white',
                ],
                'attr' => [
                    'class' => 'w-100 btn-block p-1',
                    'class' => 'col-md-12 text-center m-auto',
                ]
                
            ])


            // ->add('rateMin', IntegerType::class, [
            //     'label' => 'Min',
            //     'label_attr' => [
            //         'style' => 'color: white',
            //     ],
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Note',
            //         'min' => 0,
            //         'max' => 5,
            //         'step' => 1,
            //         'class' => 'col-md-12 text-center m-auto',
            //     ]
            // ])


            // ->add('rateMax', IntegerType::class, [
            //     'label' => 'Max',
            //     'label_attr' => [
            //         'style' => 'color: white',
            //     ],
            //     'required' => false,
            //     'attr' => [
            //         'placeholder' => 'Note',
            //         'min' => 0,
            //         'max' => 5,
            //         'step' => 1,
            //         'class' => 'col-md-12 text-center m-auto',
            //     ]
            // ])


            ->add('submit', SubmitType::class, [
                'label' => "FILTRER",
                'attr' => [
                    'class' => 'col-md-12 btn-primary mt-2',
                    'style' => 'font-weight: bold',
                ]
            ])


            // ->add('submit', SubmitType::class, [
            //         'label' => "FILTRER",
            //         'attr' => [
            //             'class' => 'btn-block btn-primary btn-lg'
            //         ]
            //     ]);

        
                

           

            

            // ->add('typeLogement', ChoiceType::class,[
            //     'choices' => [
            //         'Appartement' => 'Appartement',
            //         'Maison' => 'Maison',
            //         'Loft' => 'Loft',
            //         'Bungalows' => 'Bungalows',
            //         'Cabane' => 'Cabane',
            //         'Cottages' => 'Cottages',
            //         'Chalet' => 'Chalet',
            //         'Simplex' => 'Simplex',
            //         'Duplex' => 'Duplex',
            //         'Villa' => 'Villa',
            //         'Logement Unique' => 'Logement Unique',
            // ],
            //     'multiple'  => false,   
            //     'expanded'  => false, 
            //     // 'label' => 'Prix Min',
            //     'attr' => [
            //         // 'class' => 'w-50 btn-block btn-info p-1',
            //         'class' => 'w-50 btn-block p-1',
            //     ]
            // ])

        ;

    }

    // Pour la configuration des options liées a ce formulaire
    public function configureOptions(OptionsResolver $resolver)
    {
            // Je vais définir des valeurs par défaut
            $resolver->setDefaults([
                // data_class = quelle classe sert pour représenter nos données
                'data_class' => SearchData::class,
                // Ce formulaire utilisera une méthode GET par défaut car on veux que les paramètres passent dans l'URL pour partager une recherche
                'method' => 'GET',
                // Je désactive la protection csrf car on est dans un formulaire de recherche donc on a pas un problème de cross scripting a ce niveau là.
                'csrf_protection' => false
            ]);
    }

    public function getBlockPrefix()
    {
        // On ne veut pas de préfixe donc on demande a retourner une simple chaîne de caractère
        return '';
    }

}