<?php

namespace App\Controller\Auth;

use App\Constants\RoleConstant;
use App\Controller\AbstractController;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Request\Auth\RegistrationRequest;
use App\ValueObject\User\FullNameValue;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/register', name: 'registration.')]
class RegisterController extends AbstractController
{
    #[Route('/', name: 'form', methods: ['GET'])]
    public function create(): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'store', methods: ['POST'])]
    public function store(
        RegistrationRequest $request,
        UserRepository $userRepository
    ): JsonResponse {
        $user = new User();
        $user->setIsVerified(true);
        $user->setRoles([RoleConstant::ROLE_USER]);
        $user->setEmail($request->getEmail());
        $user->setFullName(new FullNameValue(
            $request->getName(),
            $request->getSurname(),
        ));
        $user->setPassword($request->getPlainPassword());
        $userRepository->save($user);

        return $this->json(
            new UserDTO($user),
            Response::HTTP_CREATED
        );
    }
}
