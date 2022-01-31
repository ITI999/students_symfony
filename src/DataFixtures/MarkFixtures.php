<?php

namespace App\DataFixtures;

use App\Entity\Subject;
use App\Entity\UserSubject;
use App\Repository\GroupRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MarkFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * MarkFixtures constructor.
     */
    public function __construct(
        protected UserRepository $userRepository,
        protected SubjectRepository $subjectRepository
    ) {}

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $subjects = $this->subjectRepository->findAll();
        $generator = Factory::create('ru_RU');

        foreach ($users as $user){
            foreach ($subjects as $subject){
                $mark = new UserSubject();
                $mark->setSubject($subject);
                $mark->setUser($user);
                $mark->setMark($generator->numberBetween(2,5));
                $manager->persist($mark);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SubjectFixtures::class,
            UserFixtures::class,
        ];
    }
}
