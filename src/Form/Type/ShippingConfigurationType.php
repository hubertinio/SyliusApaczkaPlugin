<?php

namespace Hubertinio\SyliusApaczkaPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShippingConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('api_key', TextType::class, [
                'required' => true,
                'label' => 'APP ID',
            ])
            ->add('api_secret', TextType::class, [
                'required' => true,
                'label' => 'APP Secret',
            ])
        ;
    }
}