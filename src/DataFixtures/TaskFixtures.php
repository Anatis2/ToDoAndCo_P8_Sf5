<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {

		$faker = \Faker\Factory::create('fr_FR');

		for($i = 1 ; $i <= 5 ; $i++) {
			$task = new Task();

			$user = $manager->getRepository(User::class)->findOneBy(["surname" => "Anonyme"]);

			$task
				->setTitle($faker->word)
				->setContent($faker->text)
				->setCreatedAt(new \DateTime())
				->setUser($user)
				;

			$manager->persist($task);
		}

        $manager->flush();
    }

	public function getDependencies()
	{
		return array(
			UserFixtures::class,
		);
	}
}
