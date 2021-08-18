<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "Votre nom de famille"))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "URL de votre photo de profil"))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe", "Choisissez votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation du mot de passe", "Confirmez votre mot de passe"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Présentez vous en quelques mots"))
            ->add('description', CKEditorType::class, $this->getConfiguration("Description détaillée", "C'est le moment de vous présenter en détail"))
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions d\'utilisation (*)',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Merci de prendre connaissance et d\'accepter les conditions d\'utilisation',
                    ]),
                ],
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
