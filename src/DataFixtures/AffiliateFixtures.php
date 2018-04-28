<?php

namespace App\DataFixtures;


use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\Affiliate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AffiliateFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('ru_RU');

        for ($i = 0; $i < 20; $i++) {
            $affiliate = new Affiliate();
            $affiliate->setUrl($faker->url);
            $affiliate->setEmail($faker->email);
            $affiliate->setToken($faker->md5);
            $affiliate->setActive($faker->boolean);

            $manager->persist($affiliate);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
