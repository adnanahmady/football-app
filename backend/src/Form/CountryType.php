<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class CountryType extends AbstractType
{
    public function __construct(
        readonly private RouterInterface $router
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->setAction($this->router->generate('create_country_v1'))
            ->setMethod('POST')
            ->add('name', Type\TextType::class, [
                'label' => 'Name',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('create', Type\SubmitType::class, [
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
