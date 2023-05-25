<?php

namespace App\Tests\Feature\Auth\Form;

use App\Tests\Traits\MigrateDatabaseTrait;
use App\Tests\WebTestCase;

class LoginTest extends WebTestCase
{
    use MigrateDatabaseTrait;

    /**
     * @test
     */
    public function it_should_redirect_unauthorized_user_to_login_page(): void
    {
        $this->client->request(
            method: 'GET',
            uri: $this->route('teams_page')
        );

        $this->assertResponseRedirects(
            $this->route(name: 'app_login', withoutHostName: false)
        );
    }
}
