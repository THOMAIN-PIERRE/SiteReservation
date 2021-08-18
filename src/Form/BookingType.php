<?php

namespace App\Form;

use App\Entity\Booking;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DataTransformer\FrenchDateTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Form\ApplicationType; // CLASSE MAISON (dans laquelle on retrouvera des méthodes utiles aux formulaires de l'application)


// Formulaire utilisateur qui permet de faire une réservation
class BookingType extends ApplicationType // RAPPEL: ApplicationType (permet la config "maison" des champs de formulaire) s'extends lui-même de AbstractType
{
    // On donne l'attribut $transformer à notre classe qui sera objet de la classe FrenchToDataTimeTransformer.php grace à fonction __construct et l'injection de dépendande de ses paramètres
    // Ainsi $transformer aura accès aux méthodes qui permettent de transformer une date de type DateTime en date au format fr et vis-versa

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer){
        // Mon transformeur, c'est le transformeur que m'a envoyé Symfony ici
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // AJOUT DES CHAMPS DU FORMULAIRE de réservation que le client remplira (getConfig() vient de la classe ApplicationType)
        $builder
            ->add('startDate', TextType::class, $this->getConfiguration("Date d'arrivée", "Date à laquelle vous comptez arriver"))
            ->add('endDate', TextType::class, $this->getConfiguration("Date de départ", "Date à laquelle vous comptez partir"))
            ->add('comment', CKEditorType::class, $this->getConfiguration(false, "Si vous avez un commentaire n'hésitez pas à nous en faire part !", ["required" => false]))
        ;

        // GESTION DES FORMATS DES CHAMPS "DATE" DU FORMULAIRE (on lui donne des date au format fr, mais pour l'insertion en bdd il à besoin d'un format objet de type DateTime)
        // Documentation sur les DataTransformers : https://symfony.com/doc/current/form/data_transformers.html
        // On récupère le champs 'startDate' ($builder->get('startDate')), sur lequel on ajoute un transfomer (addModelTransform est une méthode de l'objet builder)...
        // ... et ce "transformer" est l'attribut de notre classe (injection de dépendence du constructeur, soit FrenchToDataTimeTransformer.php)...
        // ... et permet de modifier une date

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            // 'validation_groups' => [  [ // ICI ON PEUT MODIFIER les autorisations, contraintes de notre formulaire. (ici ce formulaire accepte les validations par default et front)
            //     'Default',
            //     'front'
            //     ]
        ]);
    }
}
