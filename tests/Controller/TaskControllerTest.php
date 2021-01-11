<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
	use FixturesTrait;
	use NeedLogin;

	public function testDisplayTasks()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/tasks');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayTasksTodo()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/tasksTodo');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayTasksDone()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/tasksDone');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayTasksCreate()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/tasks/create');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

}
