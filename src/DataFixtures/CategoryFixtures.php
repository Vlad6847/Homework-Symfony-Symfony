<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $category = new Category();
            $category->setName($faker->word);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
