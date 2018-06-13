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
        $category1 = new Category();
        $category2 = new Category();

        $category1->setName('Some category');
        $manager->persist($category1);

        $category2->setName('Category');
        $manager->persist($category2);

        $manager->flush();
    }
}
