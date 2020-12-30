<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

	public function assertHasErrors(User $user, int $nbErrors = 0)
	{
		self::bootKernel();
		$error = self::$container->get('validator')->validate($user);
		$this->assertCount($nbErrors, $error);
	}

	public function testValidUserEntity()
	{
		$user = new User();

		$user
			->setSurname("Un nom")
			->setFirstname("Un prénom")
			->setEmail("email@email.com")
			;

		$this->assertHasErrors($user, 0);
	}

	public function testInvalidUserEntity()
	{
		$user = new User();

		$user
			->setSurname("")
			->setFirstname("Un prénom")
			->setEmail("email.com")
		;

		$this->assertHasErrors($user, 2);
	}

}