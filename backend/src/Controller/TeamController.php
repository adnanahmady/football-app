<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    #[Route('/teams', name: 'teams_page', methods: ['GET'])]
    public function index(
        Request $request,
        TeamRepository $teamRepository,
    ): Response {
        $limit = $this->getParameter('pagination.limit');
        $page = $request->get('page', 1);
        $paginator = $teamRepository->paginate($page, $limit);

        return $this->render('team/index.html.twig', [
            'teams' => $paginator->getIterator(),
            'lastPage' => ceil(count($teamRepository->findAll()) / $limit),
            'currentPage' => $page,
        ]);
    }
}
