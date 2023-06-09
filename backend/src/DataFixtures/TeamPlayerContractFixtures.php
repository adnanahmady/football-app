<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\Team;
use App\Entity\TeamPlayerContract;
use App\Entity\User as Player;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TeamPlayerContractFixtures extends Fixture implements DependentFixtureInterface
{
    private array $teams = [
        'madrid' => [
            'Ivor John Allchurch',
            'Viv Anderson',
            'Kenneth George Aston',
            'Alan James Ball',
            'David Beckham',
            'George Best',
            'Billy Bremner',
            'Tommy Burns',
            'Matthew Busby',
            'Bobby Charlton',
            'Brian Howard Clough',
        ],
        'Hooligans FC' => [
            'Denis Charles Scott Compton',
            'Stanley Cullis',
            'Dixie Dean',
            'Justin Fashanu',
            'Alex Ferguson',
            'Steven Gerrard',
            'Johnny Haynes',
            'Emlyn Walter Hughes',
            'Thomas Lawton',
            'Stanley Matthews',
            'Jackie Milburn',
        ],
        'everton' => [
            'Arthur Milton',
            'Bobby Moore',
            'Peter Leslie Osgood',
            'Alfred Ernest Ramsey',
            'Bobby Robson',
            'Wayne Rooney',
            'George Hedley Swindin',
            'William Ambrose Wright',
            'Michael Ballack',
            'Franz Beckenbauer',
            'Oliver Kahn',
        ],
        'red dragon' => [
            'Michael jBallack',
            'Franz jBeckenbauer',
            'Oliver jKahn',
            'Jürgen Klinsmann',
            'Lothar Matthäus',
            'Gerd Müller',
            'Birgit Prinz',
            'Fritz Walter',
            'Roberto cBaggio',
            'Fabio cCannavaro',
            'Giorgio dChinaglia',
        ],
        'blue dragon' => [
            'Roberto Baggio',
            'Fabio Cannavaro',
            'Giorgio Chinaglia',
            'Alfréd Hajós',
            'Ferenc Puskás',
            'Didier Drogba',
            'Alfredo Di Stéfano',
            'Diego Maradona',
            'Lionel Messi',
            'José Manuel Moreno',
            'Omar Sivori',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $countries = $manager
            ->getRepository(Country::class)
            ->findAll();
        $faker = Factory::create();
        $fn = fn (string $start, string $end) => $faker
            ->dateTimeBetween($start, $end)
            ->format('Y-m-d H:i:s');

        foreach ($this->teams as $team => $players) {
            $country = $countries[0];
            $team = $this->createTeam($manager, $team, $country);
            foreach ($players as $index => $player) {
                $country = $countries[$index % 3];
                $contract = new TeamPlayerContract();
                $contract->setPlayer($this->createPlayer($manager, $player));
                $contract->setTeam($team);
                $contract->setAmount(random_int(100, 999999999));
                $contract->setStartAt(new \DateTimeImmutable(
                    $fn('-5 years', '-3 months')
                ));
                $contract->setEndAt(new \DateTimeImmutable(
                    $fn('now', '+5 years')
                ));
                $manager->persist($contract);
                $manager->flush();
            }
        }
    }

    private function createPlayer(
        ObjectManager $manager,
        string $fullName
    ): Player {
        $name = explode(' ', $fullName);
        $player = new Player();
        $player->setName($name[0]);
        $player->setSurname($name[1]);
        $player->setEmail(sprintf('%s.%s@example.com', $name[0], $name[1]));

        $manager->persist($player);
        $manager->flush();

        return $player;
    }

    private function createTeam(
        ObjectManager $manager,
        string $name,
        Country $country,
    ): Team {
        $team = new Team();
        $team->setName($name);
        $team->setMoneyBalance(random_int(100, 99999));
        $team->setCountry($country);

        $manager->persist($team);
        $manager->flush();

        return $team;
    }

    public function getDependencies(): array
    {
        return [CountryFixtures::class];
    }
}
