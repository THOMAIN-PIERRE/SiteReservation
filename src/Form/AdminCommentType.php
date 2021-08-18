<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AdminCommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('content', CKEditorType::class, [
            'label' => "Contenu du commentaire",
            'label_attr' => [
                'style' => 'font-weight: bold',
            ],
            'attr' => [
                'placeholder' => "Modifer / Corriger le contenu du commentaire"
            ]
        ])
        ->add('rating', IntegerType::class, $this->getConfiguration("Evaluation de l'article", "Attribuez une note entre 0 et 5", [
            'attr' => [
                'min' => 0,
                'max' => 5,
                'step' => 1
            ],
            'label_attr' => [
                'style' => 'font-weight: bold',
            ],
        ]))
        ->add('status', ChoiceType::class, [
            'choices' => [
            'JE VALIDE LE COMMENTAIRE (le commentaire sera publié)' => 'Validated',
            'JE REJETE LE COMMENTAIRE (le commentaire ne sera pas publié)' => 'No validated',
            ],
            'label_attr' => [
                'style' => 'font-weight: bold',
            ],
            'expanded' => true,
            'multiple' => false,
            'label' => 'Statut de modération:'
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
