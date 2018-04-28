<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 4/28/18
 * Time: 9:45 AM
 */

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker      = Faker\Factory::create('ru_RU');
        $categories = $manager->getRepository(Category::class)->findAll();

        for ($i = 0; $i < 20; $i++) {
            $job = new Job();
            $job->setType($faker->jobTitle);
            $job->setCompany($faker->company);
            $job->setLogo($faker->imageUrl());
            $job->setUrl($faker->url);
            $job->setPosition($faker->word);
            $job->setLocation($faker->city);
            $job->setDescription($faker->paragraph());
            $job->setHowToApply($faker->paragraph());
            $job->setToken($faker->md5);
            $job->setPublic($faker->boolean);
            $job->setActivated($faker->boolean);
            $job->setEmail($faker->email);
            $job->setExpiresAt($faker->dateTime);
            $job->setCategory($faker->randomElement($categories));
            $manager->persist($job);
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
