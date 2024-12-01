<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    private array $categories = ['PHP 8', 'Symfony', 'Security', 'Nuxt', 'Laravel', 'JavaScript'];

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        foreach ($this->categories as $category) {
            $cat = new Category();
            $cat->setName($category)
                ->setDescription($faker->sentence());
            $manager->persist($cat);
        }

        $manager->flush();
    }
}
