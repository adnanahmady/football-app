<?php

namespace App\Controller\Api\V1;

use App\DTO\ContractPlayer\ContractDTO;
use App\Entity\TeamPlayerContract;
use App\Entity\User;
use App\Repository\TeamPlayerContractRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Request\CreatePlayerRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlayerController extends AbstractController
{
    #[Route('/api/v1/players', name: 'create_player_v1', methods: ['POST'])]
    public function index(
        CreatePlayerRequest $request,
        UserRepository $playerRepository,
        TeamRepository $teamRepository,
        TeamPlayerContractRepository $contractRepository,
    ): JsonResponse {
        $player = new User();
        $player->setName($request->getName());
        $player->setSurname($request->getSurname());
        $playerRepository->save($player, true);
        $team = $teamRepository->findOneById($request->getTeamId());
        $contract = new TeamPlayerContract();
        $contract->setPlayer($player);
        $contract->setTeam($team);
        $contract->setAmount($request->getAmount());
        $contract->setStartAt($request->getStartAt());
        $contract->setEndAt($request->getEndAt());
        $contractRepository->save($contract, true);

        return $this->json(
            ['data' => new ContractDTO($contract)],
            Response::HTTP_CREATED
        );
    }
}
