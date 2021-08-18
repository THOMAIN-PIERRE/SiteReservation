<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AdminBookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startDate', DateType::class, [
            'label' => 'Date d\'arrivée',
            'widget' => 'single_text'
        ])
        ->add('endDate', DateType::class, [
            'label' => 'Date de départ',
            'widget' => 'single_text'
        ])
        ->add('comment', CKEditorType::class, $this->getConfiguration('Message pour le propriétaire', "Si vous avez un message n'hésitez pas à nous en faire part !", ["required" => false]))
        ->add('booker', EntityType::class, [
            'label' => 'Voyageur',
            'class' => User::class,
            'choice_label' => function($user) {
                return $user->getFirstName() . " " . strtoupper($user->getLastName());
            }
        ])
        ->add('ad', EntityType::class, [
            'label' => 'Titre de l\'annonce',
            'class' => Ad::class,
            'choice_label' => 'title'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
