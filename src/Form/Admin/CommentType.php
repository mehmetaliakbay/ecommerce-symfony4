<?php

namespace App\Form\Admin;

use App\Entity\Admin\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('subject')
        ->add('comment')
        ->add('status',ChoiceType::class,[
            'choices' => [
                'New' => 'New',
                'True' => 'True',
                'False' => 'False',
                 ]
        ])
        ->add('ip')
        ->add('userid')
        ->add('productid')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
