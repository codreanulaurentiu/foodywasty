<?php
/**
 * Created by PhpStorm.
 * User: laurentiu
 * Date: 3/24/18
 * Time: 9:20 PM
 */

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Order;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'label' => 'Cantitate (Kg.)'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresă'
            ])
            ->add('type', ChoiceType::class,
                [
                    'choices' => [
                        'Consumabile' => Order::TYPE_REQUEST_FOOD,
                        'Deseuri' => Order::TYPE_REQUEST_WASTE,
                    ],
                    'label' => 'Tip'
                ]
            )
            ->add('pickUpDate', DateType::class, [
                'label' => false
            ])
            ->add('category', EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'label' => 'Categorie'
                ]);
    }
}