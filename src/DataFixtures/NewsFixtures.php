<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class NewsFixtures extends Fixture
{

    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $news = new News();

            $news
                ->setName($faker->words(3, true) . '.')
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setImage($i . '.jpg')
                ->setContent($faker->paragraphs(3, true))
                ->setSlug($this->slugger->slug($news->getName()))
                ->setUpdatedAt(new \DateTimeImmutable())
                ;

            $manager->persist($news);
        }


        $manager->flush();
    }
}
