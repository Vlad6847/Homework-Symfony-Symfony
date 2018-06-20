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
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i < 6; $i++) {
            $category = new Category();
            $category->setName('Category '.$i);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
