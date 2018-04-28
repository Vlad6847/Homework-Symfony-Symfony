<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/28/18
 * Time: 9:46 AM
 */

namespace App\DataFixtures;


use Faker;
use App\Entity\Affiliate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AffiliateFixtures extends Fixture
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
            $affiliate->setToken($faker->word);
            $affiliate->setActive($faker->boolean);
            $affiliate->setCreatedAt($faker->dateTime());

            $manager->persist($affiliate);
        }

        $manager->flush();
    }
}
