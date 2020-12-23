<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function load(ObjectManager $manager)
    {
		$user = new User();

		$user
			->setSurname("Anonyme")
			->setFirstname("Anonyme")
			->setEmail("anonyme@anonyme.fr")
			->setPassword($this->encoder->encodePassword($user, "anonyme"))
			->setRoles(["ROLE_USER"])
			->setCreatedAt(new \DateTime())
		;

		$manager->persist($user);

		$user = new User();

		$user
			->setSurname("User")
			->setFirstname("User")
			->setEmail("user@user.fr")
			->setPassword($this->encoder->encodePassword($user, "user"))
			->setRoles(["ROLE_USER"])
			->setCreatedAt(new \DateTime())
		;

		$manager->persist($user);

		$user = new User();

		$user
			->setSurname("Admin")
			->setFirstname("Admin")
			->setEmail("admin@admin.fr")
			->setPassword($this->encoder->encodePassword($user, "admin"))
			->setRoles(["ROLE_ADMIN"])
			->setCreatedAt(new \DateTime())
		;

		$manager->persist($user);

    	$faker = \Faker\Factory::create('fr_FR');

        for($i = 1 ; $i <= 5 ; $i++) {
        	$user = new User();

        	$user
				->setSurname($faker->lastName)
				->setFirstname($faker->firstName)
				->setEmail($faker->email)
				->setPassword($this->encoder->encodePassword($user,$faker->password))
				->setRoles(["ROLE_USER"])
				->setCreatedAt(new \DateTime())
			;

        	$manager->persist($user);
		}

        $manager->flush();
    }
}
