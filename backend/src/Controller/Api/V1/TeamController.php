<?php

namespace App\Controller\Api\V1;

use App\Entity\Team;
use App\EntityGroup\TeamGroup;
use App\Repository\CountryRepository;
use App\Repository\TeamRepository;
use App\Request\CreateTeamRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TeamController extends AbstractController
{
    #[Route('/api/v1/teams', name: 'create_team_v1', methods: ['POST'])]
    public function store(
        CreateTeamRequest $request,
        TeamRepository $teamRepository,
        CountryRepository $countryRepository,
    ): JsonResponse {
        $team = new Team();
        $team->setName($request->getName());
        $team->setMoneyBalance($request->getMoneyBalance());
        $team->setCountry($countryRepository->findOneById(
            $request->getCountryId()
        ));
        $teamRepository->save($team);

        return $this->json(
            ['data' => $team],
            Response::HTTP_CREATED,
            context: [ObjectNormalizer::GROUPS => [TeamGroup::CREATE]]
        );
    }
}
