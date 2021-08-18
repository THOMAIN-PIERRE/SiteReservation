<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminUserType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('firstName')
            // ->add('lastName')
            // ->add('email')
            // ->add('picture')
            // ->add('hash')
            // ->add('introduction')
            // ->add('description')
            // ->add('slug')
            // ->add('createdAt')
            // ->add('userRoles')



            ->add('firstname', TextType::class, $this->getConfiguration("Prénom", "Saisir un prénom"))
            ->add('lastname', TextType::class, $this->getConfiguration("Nom", "Saisir un nom"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Saisir un email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "Saisir l'URL de votre avatar"))
            ->add('introduction', TextType::class, $this->getConfiguration("Courte présentation", "Saisir une courte présentation"))
            ->add('description', CKEditorType::class, $this->getConfiguration("Présentation détaillée", "Saisir une présentation détaillée"))



            // ->add('Roles', ChoiceType::class,[
            //     'choices' => [
            //     'Administrateur' => '1', 
            //     'Editeur' => '8',
            //     'Utilisateur' => '9'
            // ],
            //     'multiple'  => true,   
            //     'expanded'  => true, 
            //     'label' => 'Rôle(s)',
            //     // 'mapped' => true 
            // ])


            ->add('userRoles', EntityType::class,[
                'label' => 'Rôle(s)',
                'label_attr' => [
                    'class' => 'font-weight-bold',
                ],
                'class' => Role::class,
                'choice_label' => 'title',
                // 'choice_value' => 'intitule',
                'multiple'  => true,   
                // 'expanded'  => true, 
                // 'mapped' => true
            ])

            // Modération des profils d'inscription
            ->add('status', ChoiceType::class, [
                'choices' => [
                'INSCRIPTION VALIDEE (CONTENU DU PROFIL CONFORME AU REGLEMENT)' => 'Validated',
                'INSCRIPTION REJETEE (CONTENU DU PROFIL NON CONFORME AU REGLEMENT)' => 'No validated',
                ],
                'label_attr' => [
                    'style' => 'font-weight: bold',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Statut de modération :'
            ])


            // ->add('Roles', ChoiceType::class,[
            //     'label' => 'Rôle(s)',
            //     'label_format' => [
            //         'class' => 'font-weight-bold',
            //     ],
            //     // 'class' => Role::class,
                    // Liste des rôles souhaités pour mon site
            //     'choices' => [
            //         'Utilisateur' => 'ROLE_USER',
            //         'Contributeur' => 'ROLE_CREATOR',
            //         'Administrateur' => 'ROLE_ADMIN'
            //     ],
            //     'multiple'  => true,   
            //     'expanded'  => true, 
            // ])

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
            'data_class' => User::class,
        ]);
    }
}
