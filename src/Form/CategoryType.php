<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('post')
            ->setAction('/categories')
            ->add('image', FileType::class, ['required' => false, 'mapped' => false, 'label' => false,])
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
