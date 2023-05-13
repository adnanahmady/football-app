<?php

namespace App\Form;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class TeamType extends AbstractType
{
    public function __construct(
        readonly private CountryRepository $countryRepository,
        readonly private RouterInterface $router,
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->setAction($this->router->generate('create_team_v1'))
            ->setMethod('POST')
            ->add('name', Type\TextType::class, [
                'label' => 'Name',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('money_balance', Type\NumberType::class, [
                'label' => 'Money Balance',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('country_id', Type\ChoiceType::class, [
                'label' => 'Country',
                'choices' => $this->countryRepository->findAll(),
                'choice_value' => fn (null|Country $t) => $t ? $t->getId() : 0,
                'choice_label' => fn (Country $t) => $t->getName(),
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('create', Type\SubmitType::class, [
                'row_attr' => ['class' => 'mt-3'],
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
