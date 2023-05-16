<?php

namespace App\Tests\Feature\Players;

use App\Entity\Team;
use App\Repository\TeamPlayerContractRepository;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
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
        $this->formRequest(
            name: 'john',
            surname: 'due',
            amount: random_int(100, 99999),
            team_id: 111111,
            start_at: '-3 years',
            end_at: '+3 months',
        );

        $session = $this->session()->get('errors');
        $this->assertCount(1, $session);
        $this->assertResponseRedirects();
    }

    /**
     * @test
     */
    public function when_form_request_is_sent_it_should_be_redirected_with_proper_session(): void
    {
        $this->formRequest(
            name: 'john',
            surname: 'due',
            amount: random_int(100, 99999),
            team_id: $this->createTeam()->getId(),
            start_at: '-3 years',
            end_at: '+3 months',
        );

        $session = $this->session()->get('success');
        $this->assertCount(1, $session);
        $this->assertResponseRedirects();
    }

    private function formRequest(mixed ...$parameters): Crawler
    {
        return $this->client->request(
            method: 'POST',
            uri: $this->route('create_player_v1'),
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
            'end_at is required' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'team_id' => 1,
                'start_at' => '-3 years',
            ]],
            'start_at is required' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'team_id' => 1,
                'end_at' => '+3 months',
            ]],
            'team needs to be integer' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'team_id' => 'bss40',
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'team needs to exists in system already' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'team_id' => -11,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'team is required' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'amount of the contract should be integer' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => 200.33,
                'team_id' => 1,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'amount of the contract should be more than 100 cents' => [[
                'name' => 'john',
                'surname' => 'due',
                'amount' => 100,
                'team_id' => 1,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'amount of the contract is required' => [[
                'name' => 'john',
                'surname' => 'due',
                'team_id' => 1,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'surname of the player is required' => [[
                'name' => 'john',
                'amount' => random_int(100, 99999),
                'team_id' => 1,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
            ]],
            'name of the player is required' => [[
                'name' => null,
                'surname' => 'due',
                'amount' => random_int(100, 99999),
                'team_id' => 1,
                'start_at' => '-3 years',
                'end_at' => '+3 months',
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
        if (isset($data['team_id']) && 1 === $data['team_id']) {
            $data['team_id'] = $this->createTeam()->getId();
        }
        $data = $this->formatDate($data, 'start_at');
        $data = $this->formatDate($data, 'end_at');
        $this->request(...$data);

        $this->assertResponseStatusCodeSame(
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
        $this->assertCount(1, $this->response('errors'));
    }

    private function formatDate(array $data, string $key): array
    {
        if (isset($data[$key]) && is_string($data[$key])) {
            $data[$key] = format($data[$key]);
        }

        return $data;
    }

    /**
     * @test
     */
    public function team_information_should_be_in_expected_form(): void
    {
        $this->requestCreatingPlayer();

        $base = $this->response('data.team');
        $this->assertArrayHasKeys(['id', 'name'], $base);
    }

    /**
     * @test
     */
    public function player_information_should_be_in_expected_form(): void
    {
        $this->requestCreatingPlayer();

        $base = $this->response('data.player');
        $this->assertArrayHasKeys(['id', 'full_name'], $base);
    }

    /**
     * @test
     */
    public function it_should_be_represented_in_specified_form(): void
    {
        $this->requestCreatingPlayer();

        $base = $this->response('data');
        $this->assertArrayHasKeys([
            'id',
            'player',
            'team',
            'amount',
            'start_at',
            'end_at',
        ], $base);
    }

    private function requestCreatingPlayer(): void
    {
        $team = $this->createTeam();

        $this->request(
            name: 'john',
            surname: 'due',
            amount: random_int(100, 99999),
            team_id: $team->getId(),
            start_at: format('-3 years'),
            end_at: format('+3 months')
        );
    }

    private function createTeam(): Team
    {
        /* @var Team */
        return $this->factory(Team::class)->create();
    }

    /**
     * @test
     */
    public function created_country_should_get_stored_in_database(): void
    {
        $this->requestCreatingPlayer();

        $players = $this->getContainer()
            ->get(UserRepository::class)
            ->findAll();
        $teams = $this->getContainer()
            ->get(TeamRepository::class)
            ->findAll();
        $contracts = $this->getContainer()
            ->get(TeamPlayerContractRepository::class)
            ->findAll();

        $this->assertCount(1, $players);
        $this->assertCount(1, $teams);
        $this->assertCount(1, $contracts);
    }

    /**
     * @test
     */
    public function it_can_create_required_team(): void
    {
        $this->requestCreatingPlayer();

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
            uri: $this->route('create_player_v1'),
            parameters: $parameters
        );
    }
}
