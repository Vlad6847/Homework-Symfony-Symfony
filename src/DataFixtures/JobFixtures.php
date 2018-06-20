<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;
use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class JobFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('us_US');
        $categories = $manager->getRepository(Category::class)->findAll();
        $positions = ['Web Developer', 'Web Designer', 'Scrum master', 'Project Manager', 'Team Lead'];

        for ($i = 0; $i < 500; $i++) {
            $job = new Job();
            $job->setType($faker->randomElement(Job::TYPES));
            $job->setCompany($faker->company);
            $job->setLogo('default.png');
            $job->setUrl($faker->url);
//            $job->setPosition($faker->randomElement($positions));
            $job->setPosition($faker->jobTitle);
            $job->setLocation($faker->city);
            $job->setDescription($faker->paragraph(1));
            $job->setHowToApply($faker->paragraph(1));
            $job->setToken($faker->md5);
            $job->setPublic(true);
            $job->setActivated(true);
            $job->setEmail($faker->email);
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
