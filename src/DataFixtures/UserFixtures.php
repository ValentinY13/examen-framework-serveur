<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private object $hasher;
    private array $genders = ['m', 'f'];
    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $gender = $faker->randomElement($this->genders);

            $user
                ->setFirstName($faker->firstName($gender))
                ->setLastName($faker->lastName($gender))
                ->setEmail($user->getFirstName() . '.' . $user->getLastName() . '@' . $faker->safeEmailDomain)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setImage('0'. ($i + 10) . $gender . '.jpg')
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable())
                ->setLastLogAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
                ->setDisabled($faker->boolean('10'))
            ;
            $manager->persist($user);
        }
        $manager->flush();

        // Admin
        $user
            ->setEmail('admin@example.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($user, 'password'))
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setImage('014m.jpg')
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable())
            ->setLastLogAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-30 days', 'now')))
            ->setDisabled(false)
        ;
        $manager->persist($user);
        $manager->flush();
    }
}
