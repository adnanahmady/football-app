<?php

namespace App\Tests\Feature\Teams;

use App\Entity\Country;
use App\Repository\TeamRepository;
use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class CreateTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    private null|KernelBrowser $client = null;

    /**
     * @test
     */
    public function when_form_request_is_sent_it_should_be_redirected_with_proper_session(): void
    {
        $this->client->request(
            method: 'POST',
            uri: '/api/v1/teams',
            parameters: [
                'name' => 'sample team',
                'money_balance' => 1200,
                'country_id' => $this->createCountry()->getId(),
            ]
        );
        $session = $this->session()->get('success');

        $this->assertCount(1, $session);
        $this->assertResponseRedirects();
    }

    private function session(): SessionInterface
    {
        return $this->client->getRequest()->getSession();
    }

    /**
     * @test
     */
    public function created_team_should_get_stored_in_database(): void
    {
        $this->request(
            name: 'sample team',
            money_balance: 1200,
            country_id: $this->createCountry()->getId()
        );

        $teams = $this->getContainer()
            ->get(TeamRepository::class)
            ->findAll();

        $this->assertCount(1, $teams);
    }

    public function dataProviderForValidationTest(): array
    {
        return [
            'name of the team is required' => [[
                'name' => null,
                'money_balance' => 1200,
                'country_id' => 1,
            ]],
            'name of the team can not be array' => [[
                'name' => ['sample', 'team', 'arrayed', 'here'],
                'money_balance' => 1200,
                'country_id' => 1,
            ]],
            'name of the team can not be less than 4 characters' => [[
                'name' => 'te',
                'money_balance' => 1200,
                'country_id' => 1,
            ]],
            'money balance is required' => [[
                'name' => 'team',
                'country_id' => 1,
            ]],
            'money balance should be integer' => [[
                'name' => 'team',
                'money_balance' => 1200.33,
                'country_id' => 1,
            ]],
            'money balance should be numeric' => [[
                'name' => 'team',
                'money_balance' => 'adddd1200',
                'country_id' => 1,
            ]],
            'country_id is required' => [[
                'name' => 'sample team',
                'money_balance' => 1200,
            ]],
            'country_id needs to exist in system' => [[
                'name' => 'sample team',
                'money_balance' => 1200,
                'country_id' => 2,
            ]],
        ];
    }

    /**
     * @dataProvider dataProviderForValidationTest
     *
     * @test
     */
    public function data_validation(array $data): void
    {
        if (isset($data['country_id']) && 1 === $data['country_id']) {
            $data['country_id'] = $this->createCountry()->getId();
        }
        $this->request(...$data);

        $this->assertCount(1, $this->response('errors'));
        $this->assertResponseStatusCodeSame(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @test
     */
    public function team_country_should_be_represented_in_specified_form(): void
    {
        $this->request(
            name: 'sample team',
            money_balance: 1200,
            country_id: $this->createCountry()->getId()
        );

        $base = $this->response('data.country');
        $this->assertArrayHasKeys(['id', 'name'], $base);
        $this->assertArrayNotHasKey('teams', $base);
    }

    /**
     * @test
     */
    public function created_team_response_should_be_in_specified_form(): void
    {
        $this->request(
            name: 'sample team',
            money_balance: 1200,
            country_id: $this->createCountry()->getId()
        );

        $this->assertArrayHasKeys([
            'id',
            'name',
            'money_balance',
            'country',
        ], $this->response('data'));
    }

    /**
     * @test
     */
    public function it_can_create_required_team(): void
    {
        $this->request(
            name: 'sample team',
            money_balance: 1200,
            country_id: $this->createCountry()->getId()
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    private function getBootedKernel(): KernelInterface
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return $this->client->getKernel();
    }

    private function response(string $path = ''): array
    {
        $array = json_decode(
            $this->client->getResponse()->getContent(),
            true
        );

        if (!strlen($path)) {
            return $array;
        }

        foreach (explode('.', $path) as $key) {
            $array = $array[$key];
        }

        return $array;
    }

    private function request(mixed ...$parameters): Crawler
    {
        return $this->client->jsonRequest(
            method: 'POST',
            uri: '/api/v1/teams',
            parameters: $parameters
        );
    }

    private function createCountry(): array|object
    {
        return $this->factory(Country::class)->create();
    }
}
