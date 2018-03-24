<?php

namespace AppBundle\Form;


use AppBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class)
            ->add('type', ChoiceType::class,
                [
                    'choices' => [
                        'Doneaza mancare' => Order::TYPE_ADD_FOOD,
                        'Comanda mancare' => Order::TYPE_REQUEST_FOOD,
                        'Doneaza deseuri' => Order::TYPE_ADD_WASTE,
                        'Comanda deseuri' => Order::TYPE_REQUEST_WASTE
                    ]
                ]
            )
            ->add('pickUpDate', DateType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Order',
        ]);
    }
}