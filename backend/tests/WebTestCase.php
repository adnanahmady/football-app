<?php

namespace App\Tests;

use App\Service\Finder\TraitMethodFinder;
use App\Tests\Traits\HasFactoryTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;

class WebTestCase extends BaseTestCase
{
    use HasFactoryTrait;

    private TraitMethodFinder $finder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->finder = new TraitMethodFinder($this);
        $this->finder->execute(
            'setUp',
            fn ($method) => $this->$method(),
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->finder->execute(
            'tearDown',
            fn ($method) => $this->$method(),
        );
    }
}
