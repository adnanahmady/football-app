<?php

namespace App\Controller\Api\V1;

use App\Entity\Country;
use App\EntityGroup\CountryGroup;
use App\Repository\CountryRepository;
use App\Request\CreateCountryRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class CountryController extends AbstractController
{
    #[Route('/api/v1/countries', name: 'create_country_v1', methods: ['POST'])]
    public function index(
        CreateCountryRequest $request,
        CountryRepository $countryRepository,
    ): JsonResponse {
        $country = new Country();
        $country->setName($request->getName());
        $countryRepository->save($country, true);

        return $this->json(
            ['data' => $country],
            Response::HTTP_CREATED,
            context: [ObjectNormalizer::GROUPS => [CountryGroup::CREATE]]
        );
    }
}
