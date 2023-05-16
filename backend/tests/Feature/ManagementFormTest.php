<?php

namespace App\Tests\Feature;

use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ManagementFormTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    /**
     * @test
     */
    public function create_player_form_needs_to_exist_in_the_page(): void
    {
        $this->request();
        $content = $this->response();

        $this->assertFormExistsInString(
            $content,
            'Create Player',
            'player',
            $this->route('create_player_v1')
        );
    }

    /**
     * @test
     */
    public function create_team_form_needs_to_exist_in_the_page(): void
    {
        $this->request();
        $content = $this->response();

        $this->assertFormExistsInString(
            $content,
            'Create Team',
            'team',
            $this->route('create_team_v1')
        );
    }

    /**
     * @test
     */
    public function create_country_form_needs_to_exist_in_the_page(): void
    {
        $this->request();
        $content = $this->response();

        $this->assertFormExistsInString(
            $content,
            'Create Country',
            'country',
            $this->route('create_country_v1')
        );
    }

    private function assertFormExistsInString(
        false|string $content,
        string $title,
        string $name,
        string $route
    ): void {
        $this->assertStringContainsString($title, $content);
        $this->assertStringContainsString(
            $this->buildFormString(
                $name,
                $route
            ),
            $content
        );
    }

    private function buildFormString(string $name, string $route): string
    {
        return sprintf(
            '<form name="%s" method="post" action="%s">',
            $name,
            $route
        );
    }

    /**
     * @test
     */
    public function page_should_load_correctly(): void
    {
        $this->request();

        $this->assertResponseIsSuccessful();
    }

    private function request(): Crawler
    {
        return $this->client->request(
            'GET',
            $this->route('create_player_page')
        );
    }

    private function response(): string|false
    {
        return $this->client->getResponse()->getContent();
    }
}
