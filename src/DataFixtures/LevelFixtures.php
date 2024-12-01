<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LevelFixtures extends Fixture
{
    private array $levels = ['BES', 'BACHELIER', 'MASTER', 'CESS'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach ($this->levels as $level) {
            $lvl = new Level();
            $lvl->setName($level)
                ->setPrerequisite($level);
            $manager->persist($lvl);
        }

        $manager->flush();
    }
}
