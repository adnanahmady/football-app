<?php

namespace App\Tests\Feature;

use App\Tests\WebTestCase;

class HomePageTest extends WebTestCase
{
    /** @test */
    public function applicationShowsThatIsUnderConstructions(): void
    {
        $crawler = static::createClient()->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}
