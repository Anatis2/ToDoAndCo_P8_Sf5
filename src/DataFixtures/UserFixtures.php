<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = \Faker\Factory::create('fr_FR');

        for($i = 1 ; $i <= 5 ; $i++) {
        	$user = new User();

        	$user
				->setSurname($faker->lastName)
				->setFirstname($faker->firstName)
				->setEmail($faker->email)
				->setPassword($faker->password)
				->setRoles(["ROLE_USER"])
				->setCreatedAt(new \DateTime())
			;

        	$manager->persist($user);
		}

        $manager->flush();
    }
}
