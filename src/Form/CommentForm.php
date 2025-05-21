<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Comment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('author',TextType::class,[
                'label' => 'votre pseudo',
                 'attr'=> [
                    'class' => 'form-control',
                    'placeholder' =>'ajouter votre nom'
                 ]
            ])
            ->add('content',TextType::class,[
                  'label' => 'commenter',
                 'attr'=> [
                    'class' => 'form-control',
                    'placeholder' =>'ajouter votre commentaire',
                    'style' => ''
                 ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
