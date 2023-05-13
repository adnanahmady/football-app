<?php

namespace App\Form;

use App\Entity\Team;
use App\Repository\TeamRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Routing\RouterInterface;

class PlayerType extends AbstractType
{
    public function __construct(
        readonly private TeamRepository $teamRepository,
        readonly private RouterInterface $router,
    ) {
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->setAction($this->router->generate('create_player_v1'))
            ->setMethod('POST')
            ->add('name', Type\TextType::class, [
                'label' => 'Name',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('surname', Type\TextType::class, [
                'label' => 'Surname',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('amount', Type\NumberType::class, [
                'label' => 'Contract amount',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('team_id', Type\ChoiceType::class, [
                'label' => 'Team',
                'choices' => $this->teamRepository->findAll(),
                'choice_value' => fn (null|Team $t) => $t ? $t->getId() : 0,
                'choice_label' => fn (Team $t) => $t->getName(),
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('start_at', Type\DateTimeType::class, [
                'label' => 'Start Date',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('end_at', Type\DateTimeType::class, [
                'label' => 'End Date',
                'row_attr' => ['class' => 'form-group mt-3'],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('create', Type\SubmitType::class, [
                'row_attr' => ['class' => 'mt-3'],
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }
}
