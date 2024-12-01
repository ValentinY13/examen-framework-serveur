<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    private array $users = [];
    private array $courses = [];

    public function load(ObjectManager $manager): void
    {
        $this->users = $manager->getRepository(User::class)->findAll();
        $this->courses = $manager->getRepository(Course::class)->findAll();
        $faker = Factory::create();
        foreach ($this->courses as $course) {
            for ($i = 0; $i < $faker->numberBetween(1, 10); $i++) {
                $cmt = new Comment();
                $cmt->setUser($faker->randomElement($this->users))
                    ->setCourse($course)
                    ->setContent($faker->realText($faker->numberBetween(10, 50)))
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setDisabled($faker->boolean(2))
                    ->setCotation($faker->numberBetween(1,5))
                ;
                $manager->persist($cmt);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            CourseFixtures::class,
        ];
    }
}
