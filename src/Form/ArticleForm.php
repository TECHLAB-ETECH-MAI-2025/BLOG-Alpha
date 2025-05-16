<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'label' => 'Titre de l\'article',
                'attr'  =>[
                    'class'=> 'form-control',
                    'placeholder' => 'Entrez un titre pour votre article',
					'required' => true,
                    'style' => 'margin: 10px',
                ]
            ])

            ->add('content',TextType::class,[
                'label' => 'Que contient votre article',
                'attr'  => [
                    'class' => 'form-control',
                    'placeholder' => 'Ecrivez tout ce que vous voulez a propos de votre article ici',
                    'style' => ' height: 100px; margin: 10px',
                    'required' => true,
                ]
            ])

            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégories',
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'attr' => [
					'class' => 'form-check',
                    'style' => 'display: flex; flex-direction: column;'
                ],
                'label_attr' => [
						'class' => 'form-check-label'
					]            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
