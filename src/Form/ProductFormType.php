<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Service\CategoryRender;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    /**
     * @var CategoryRender
     */
    private CategoryRender $categoryRender;

    public function __construct(CategoryRender $categoryRender)
    {
        $this->categoryRender = $categoryRender;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('content')
            ->add('price', MoneyType::class, [
                'currency' => 'RUB'
            ])
            ->add('photo')
            /*->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Выберите категорию',
            ])*/
            ->add('category', ChoiceType::class, [
                'choices' => $this->categoryRender->getRepo()->childrenHierarchy(null, false)
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
