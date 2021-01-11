<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolation;

class UserTest extends KernelTestCase
{
	use FixturesTrait;

	public function assertHasErrors(User $user, int $nbErrors = 0)
	{
		self::bootKernel();
		$errors = self::$container->get('validator')->validate($user);
		$messages = [];
		/** @var ConstraintViolation $error */
		foreach($errors as $error) {
			$messages[] = $error->getPropertyPath() . " => " . $error->getMessage();
		}
		$this->assertCount($nbErrors, $errors, implode(', ', $messages));
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

	public function testUniqueConstraint()
	{
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		$user = $users['user_emailExists'];

		$user
			->setSurname("Un nom")
			->setFirstname("Un prénom")
			->setEmail("test@unique.com")
			;
		$this->assertHasErrors($user, 1);
	}

}