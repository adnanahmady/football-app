<?php

namespace App\Tests\Feature\Teams;

use App\Entity\Team;
use App\Entity\TeamPlayerContract;
use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ListTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    private null|KernelBrowser $client = null;

    /**
     * @test
     */
    public function user_can_change_page(): void
    {
        $teams = $this->createTeamsWithPlayers(10, 2);

        $crawler = $this->request(page: 2);

        $this->assertStringContainsString(
            $teams[5]->getName(),
            $crawler->html()
        );
        $this->assertStringContainsString(
            $teams[6]->getName(),
            $crawler->html()
        );
        $this->assertStringNotContainsString(
            $teams[9]->getName(),
            $crawler->html()
        );
    }

    /**
     * @test
     */
    public function user_can_paginate_items(): void
    {
        $this->createTeamsWithPlayers(4, 4);

        $crawler = $this->request();

        $this->assertStringContainsString(
            '<ul class="pagination',
            $crawler->html()
        );
    }

    /**
     * @test
     */
    public function each_teams_players_should_exist_in_list(): void
    {
        $teams = $this->createTeamsWithPlayers(2);

        $crawler = $this->request();

        foreach ($teams as $team) {
            foreach ($team->getTeamPlayerContracts() as $contract) {
                $this->assertStringContainsString(
                    sprintf(
                        '%s %s',
                        $contract->getPlayer()->getName(),
                        $contract->getPlayer()->getSurname(),
                    ),
                    $crawler->text()
                );
            }
        }
    }

    private function createTeamsWithPlayers(
        int $teamCount = 1,
        int $playerCount = 11
    ): array {
        $teams = $this->createTeam(count: $teamCount);

        foreach ($teams as $team) {
            $contracts = $this
                ->factory(TeamPlayerContract::class)
                ->setCount($playerCount)
                ->create(['team' => $team]);
            foreach ($contracts as $contract) {
                $team->addTeamPlayerContract($contract);
            }
        }

        return $teams;
    }

    /**
     * @test
     */
    public function it_lists_teams_in_the_page(): void
    {
        $teams = $this->createTeam(count: 10);
        $crawler = $this->request();

        $this->assertStringContainsString(
            $teams[9]->getName(),
            $crawler->text()
        );
    }

    /** @return array<Team>|Team */
    private function createTeam(int $count): array|Team
    {
        return $this
            ->factory(Team::class)
            ->setCount($count)
            ->create();
    }

    /**
     * @test
     */
    public function it_loads_correctly(): void
    {
        $this->request();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    private function getBootedKernel(): KernelInterface
    {
        if (null === $this->client) {
            $this->client = static::createClient();
        }

        return $this->client->getKernel();
    }

    private function request(int $page = 1): Crawler
    {
        return $this->client->request(
            method: 'GET',
            uri: '/teams',
            parameters: compact('page')
        );
    }
}
