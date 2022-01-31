<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SubjectFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create('ru_RU');

        for($i = 0; $i < 3; $i++){
            $group = new Subject();
            $group->setTitle($generator->word);
            $manager->persist($group);
        }

        $manager->flush();
    }
}
