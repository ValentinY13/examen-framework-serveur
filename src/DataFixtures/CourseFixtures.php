<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Course;
use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class CourseFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categories = $manager->getRepository(Category::class)->findAll();

        $levels = $manager->getRepository(Level::class)->findAll();

        for ($i = 1; $i <= 35; $i++) {
            $course = new Course();

            $course->setName($faker->words(3, true) . '.')
                ->setSmallDescription($faker->paragraph())
                ->setFullDescription($faker->paragraphs(4, true))
                ->setDuration($faker->numberBetween(1, 4))
                ->setPrice($faker->numberBetween(50, 500))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setPublished($faker->boolean(90))
                ->setSlug($this->slugger->slug($course->getName()))
                ->setImage($i . '.jpg')
                ->setProgram('defaultPDF.pdf')
                ->setCategory($categories[array_rand($categories)])
                ->setLevel($levels[array_rand($levels)])
                ->setUpdatedAt(new \DateTimeImmutable());

            $manager->persist($course);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [
//            UserFixtures::class,
            CategoryFixtures::class,
            LevelFixtures::class,
//            CommentsFixtures::class,
        ];
    }
}
