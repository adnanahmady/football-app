<?php

namespace App\Controller\Api\V1;

use App\Controller\AbstractController;
use App\Entity\Country;
use App\EntityGroup\CountryGroup;
use App\Repository\CountryRepository;
use App\Request\CreateCountryRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CountryController extends AbstractController
{
    #[Route('/api/v1/countries', name: 'create_country_v1', methods: ['POST'])]
    public function index(
        CreateCountryRequest $request,
        CountryRepository $countryRepository,
    ): JsonResponse|RedirectResponse {
        $country = new Country();
        $country->setName($request->getName());
        $countryRepository->save($country, true);

        return $request->expectsJson() ? $this->json(
            ['data' => $country],
            Response::HTTP_CREATED,
            context: [ObjectNormalizer::GROUPS => [CountryGroup::CREATE]]
        ) : $this->redirectBack($request, 'Country created successfully!');
    }
}
