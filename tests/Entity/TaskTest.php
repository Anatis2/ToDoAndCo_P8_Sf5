<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
	public function testValidTaskEntity()
	{
		$task = new Task();

		$task
			->setTitle("Un titre")
			->setContent("Du contenu")
			;

		self::bootKernel();
		$error = self::$container->get('validator')->validate($task);
		$this->assertCount(0, $error);
	}

	public function testInvalidTaskEntity()
	{
		$task = new Task();

		$task
			->setTitle("")
			->setContent("Du contenu")
		;

		self::bootKernel();
		$error = self::$container->get('validator')->validate($task);
		$this->assertCount(1, $error);
	}
}