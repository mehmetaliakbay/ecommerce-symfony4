<?php

namespace App\Form\Admin;

use App\Entity\Admin\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('parentid')
        ->add('title', TextType::class,['label'=>'Category Name'])
        ->add('keywords')
        ->add('description')
        ->add('status', ChoiceType::class,[
            'choices' => [
                'True' => 'True',
                'False' => 'False'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
