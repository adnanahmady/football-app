<?php

namespace App\Tests\Feature\Auth\Form;

use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

class RegistrationTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    /**
     * @test
     */
    public function user_can_register_with_correct_data(): void
    {
        $this->registerUser();

        $data = $this->getResponseData();

        $this->assertArrayHasKeys([
            'id',
            'email',
            'full_name',
            'user_roles',
            'is_verified',
        ], $data);
    }

    /**
     * @test
     */
    public function it_should_return_proper_status_code(): void
    {
        $this->registerUser();

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    private function registerUser(array $data = [
        'name' => 'some',
        'surname' => 'user',
        'email' => 'some@user.com',
        'plainPassword' => 'password',
        'agreeTerms' => '1',
    ]): Crawler
    {
        $uri = fn ($endpoint) => $this->route('registration.' . $endpoint);

        return $this->client->jsonRequest(
            method: 'POST',
            uri: $uri('store'),
            parameters: $data
        );
    }

    private function getResponseContent(): string|false
    {
        return $this->client->getResponse()->getContent();
    }

    private function getResponseData(): array|false
    {
        return jsonToArray($this->getResponseContent());
    }
}
