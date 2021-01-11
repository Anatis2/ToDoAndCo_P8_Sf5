<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Traits\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
	use FixturesTrait;
	use NeedLogin;

	public function testDisplayUsers()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$client->request('GET', '/users');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayUsersForbidden()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/users');
		$this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
	}

	public function testDisplayUsersCreate()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$client->request('GET', '/users/create');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayUsersProfile()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/profile');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testDisplayUsersProfileAdmin()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$client->request('GET', '/profile');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}
}