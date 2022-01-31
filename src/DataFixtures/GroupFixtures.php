<?php

namespace App\DataFixtures;

use App\Entity\Group;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $generator = Factory::create('ru_RU');

        for($i = 0; $i < 3; $i++){
            $group = new Group();
            $group->setTitle($generator->word);
            $manager->persist($group);
        }

        $manager->flush();
    }
}
