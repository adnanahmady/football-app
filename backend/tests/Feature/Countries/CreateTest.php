<?php

namespace App\Tests\Feature\Countries;

use App\Repository\CountryRepository;
use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreateTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    /**
     * @test
     */
    public function violations_with_form_request_should_redirect_with_proper_session_data(): void
    {
        $this->formRequest(name: 'g');

        $session = $this->session()->get('errors');
        $this->assertCount(1, $session);
        $this->assertResponseRedirects();
    }

    /**
     * @test
     */
    public function when_form_request_is_sent_it_should_be_redirected_with_proper_session(): void
    {
        $this->formRequest(name: 'german');

        $session = $this->session()->get('success');
        $this->assertCount(1, $session);
        $this->assertResponseRedirects();
    }

    public function formRequest(mixed ...$parameters): Crawler
    {
        return $this->client->request(
            method: 'POST',
            uri: $this->route('create_country_v1'),
            parameters: $parameters
        );
    }

    private function session(): SessionInterface
    {
        return $this->client->getRequest()->getSession();
    }

    public function dataProviderForValidationTest(): array
    {
        return [
            'name of the country is required' => [[
                'name' => null,
            ]],
            'name of the country can not be array' => [[
                'name' => ['sample', 'team', 'arrayed', 'here'],
            ]],
            'name of the country can not be less than 3 characters' => [[
                'name' => 'te',
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
        $this->request(...$data);

        $this->assertResponseStatusCodeSame(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * @test
     */
    public function team_country_should_be_represented_in_specified_form(): void
    {
        $this->request(name: 'sample team');

        $base = $this->response('data');
        $this->assertArrayHasKeys(['id', 'name'], $base);
        $this->assertArrayNotHasKey('teams', $base);
    }

    /**
     * @test
     */
    public function created_country_should_get_stored_in_database(): void
    {
        $this->request(name: 'sample country');

        $countries = $this->getContainer()
            ->get(CountryRepository::class)
            ->findAll();

        $this->assertCount(1, $countries);
    }

    /**
     * @test
     */
    public function it_can_create_required_team(): void
    {
        $this->request(name: 'sample team');

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
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
            uri: '/api/v1/countries',
            parameters: $parameters
        );
    }
}
