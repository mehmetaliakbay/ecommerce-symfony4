<?php

namespace App\Form\Admin;

use App\Entity\Admin\Category;
use App\Entity\Admin\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'title',
        ])
        ->add('title')
        ->add('keywords')
        ->add('description')
        ->add('price')
        ->add('amount')
        ->add('email')
        ->add('status', ChoiceType::class, [
            'choices' => [
                'True' => 'True',
                'False' => 'False'
            ]
        ])
        ->add('star', ChoiceType::class, [
            'choices' => [
                '1 Yıldız' => '1',
                '2 Yıldız' => '2',
                '3 Yıldız' => '3',
                '4 Yıldız' => '4',
                '5 Yıldız' => '5',

            ]
        ])
        ->add('address')
        ->add('image', FileType::class, [
            'label' => 'Product Main Image',

            // unmapped means that this field is not associated to any entity property
            'mapped' => false,
            // make it optional so you don't have to re-upload the PDF file
            // everytime you edit the Product details
            'required' => false,
            // unmapped fields can't define their validation using annotations
            // in the associated entity, so you can use the PHP constraint classes
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid Image document',
                ])
            ],
        ])
        ->add('country')
        ->add('location')
        ->add('detail', CKEditorType::class, array(
            'config' => array(
                'uiColor' => '#ffffff',
            ),
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
