<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use App\Entity\User;
use App\Repository\GroupRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * UserFixtures constructor.
     */
    public function __construct(protected GroupRepository $groupRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $generator = Factory::create('ru_RU');
        $groups = $this->groupRepository->findAll();
        foreach($groups as $group){
            for ($i = 0; $i < 10; $i++) {
                $user = new User();
                $user->setName($generator->name);
                $user->setBirthday($generator->dateTime);
                $user->setRole(1);
                $user->setStudyGroup($group);
                $user->setEmail($generator->email);
                $user->setPassword($generator->password);
                $user->setAddress([
                    'city' => $generator->city,
                    'street' => $generator->streetName,
                    'house' => $generator->numberBetween(1,99)
                ]);
                $manager->persist($user);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            GroupFixtures::class
        ];
    }


}
