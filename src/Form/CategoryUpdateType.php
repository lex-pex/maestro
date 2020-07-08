<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->setMethod('put')
        ->setAction('/categories')
        ->add('image', FileType::class, ['required' => false, 'mapped' => false, 'label' => false])
        ->add('image_del',  CheckboxType::class, ['label' => 'Delete Image', 'mapped' => false, 'required' => false])
        ->add('id', HiddenType::class, ['data' => $options['data']->getId()])
        ->add('name', TextType::class)
        ->add('description', TextareaType::class, ['attr' => ['rows' => 5]])
        ->add('status', IntegerType::class)
        ->add('alias', TextType::class, ['attr' => ['placeholder' => 'On empty fills automatically']])
        ->add('save', SubmitType::class, ['label' => 'Update Item']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
