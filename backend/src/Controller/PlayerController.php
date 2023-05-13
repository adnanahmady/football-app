<?php

namespace App\Controller;

use App\Form\CountryType;
use App\Form\PlayerType;
use App\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    #[Route('/players/create', name: 'create_player_page', methods: ['GET'])]
    public function create(): Response
    {
        return $this->render('team/create.html.twig', [
            'createTeamForm' => $this->createForm(TeamType::class),
            'createCountryForm' => $this->createForm(CountryType::class),
            'createPlayerForm' => $this->createForm(PlayerType::class),
        ]);
    }
}
