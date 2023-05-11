<?php

namespace App\Tests\Feature;

use App\Tests\WebTestCase;

class HomePageTest extends WebTestCase
{
    /** @test */
    public function application_shows_that_is_under_constructions(): void
    {
        $crawler = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
