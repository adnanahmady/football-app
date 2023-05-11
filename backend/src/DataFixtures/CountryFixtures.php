<?php

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture
{
    private array $countries = [
        'armenia',
        'germany',
        'france',
        'england',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->countries as $name) {
            $country = new Country();
            $country->setName($name);

            $manager->persist($country);
            $manager->flush();
        }
    }
}
