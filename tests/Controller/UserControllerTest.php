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

	public function testDisplayUsersCreateForbidden()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/users/create');
		$this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
	}

	public function testUserCreate()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/users/create');
		$form = $crawler->selectButton('Ajouter')->form([
			'user_create[surname]' => "nomTestUserCreate",
			'user_create[firstname]' => "prenomTestUserCreate",
			'user_create[email]' => "nomTestUserCreate@prenomTestUserCreate.fr",
			'user_create[roles]' => "ROLE_USER",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/users');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}

	public function testUserCreateRestrictedAccess()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$client->request('GET', '/users/create');

		$this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
	}

	public function testUserEditProfile()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/user/2/edit');
		$form = $crawler->selectButton('Modifier')->form([
			'user_edit[surname]' => "nomTestUserCreate",
			'user_edit[firstname]' => "prenomTestUserCreate",
			'user_edit[email]' => "nomTestUserCreate@prenomTestUserCreate.fr",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}

	public function testUserEditPassword()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/user/2/editPassword');
		$form = $crawler->selectButton('Modifier')->form([
			'user_password_edit[password][first]' => "newPWD",
			'user_password_edit[password][second]' => "newPWD",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}

	public function testEditRoleAction()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/users/2/editRole');
		$form = $crawler->selectButton('Modifier')->form([
			'user_role_edit[roles]' => "ROLE_ADMIN",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/users');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
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

	public function testUserDelete()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_admin'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/users');
		$form = $crawler->selectButton('Supprimer')->form([
			'_method' => "DELETE",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/users');
	}

	/*public function testUserCreateSendMail()
	{
		$client = static::createClient();
		$client->enableProfiler();
		$client->request('GET', '/users/create');
		$mailCollector = $client->getProfile()->getCollector('mailer');
		$this->assertEquals(1, $mailCollector->getMessageCount());
	}*/
}