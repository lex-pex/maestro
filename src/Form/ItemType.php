<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Items;
use App\Entity\Users;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * ItemType constructor.
     * @param ContainerInterface $container to get the doctrine
     */
    public function __construct(ContainerInterface $container) {
        $this->doctrine = $container->get('doctrine');
    }

    /**
     * Array key-value for form-select from doctrine result-set
     * @param $arr
     * @return array
     */
    private function selectEntries($arr) {
        $map = [];
        for($i = 0; $i < count($arr); $i++)
            $map[$arr[$i]->getName()] = $arr[$i]->getId();
        return $map;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $users = $this->selectEntries(Users::all($this->doctrine, 'asc'));
        $categories = $this->selectEntries(Categories::allExceptMain($this->doctrine, 'asc'));
        $builder
            ->setMethod('post')
            ->setAction('/items')
            ->add('title', TextType::class)
            ->add('text', TextareaType::class, ['attr' => ['rows' => 5]])
            ->add('image', FileType::class, ['required' => false, 'mapped' => false])
            ->add('userId', ChoiceType::class, ['label' => 'Author (admin option)', 'choices'  => $users])
            ->add('categoryId', ChoiceType::class, ['choices'  => $categories])
            ->add('status', IntegerType::class)
            ->add('alias', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'On empty fills automatically']])
            ->add('save', SubmitType::class, ['label' => 'Create Item'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Items::class,
        ]);
    }
}
