<?php

namespace App\Tests\Controller;

use App\Entity\Task;
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

	public function testTaskCreate()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/tasks/create');
		$form = $crawler->selectButton('Ajouter')->form([
			'task[title]' => "un titre",
			'task[content]' => "du contenu",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/tasks');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}

	public function testTaskEdit()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/tasks/1/edit');
		$form = $crawler->selectButton('Modifier')->form([
			'task[title]' => "titreTest",
			'task[content]' => "contenuTest",
		]);

		$client->submit($form);
		$this->assertResponseRedirects('/tasks');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}

	public function testTaskDelete()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/tasks');
		$form = $crawler->selectButton('Supprimer')->form([
			'_method' => "DELETE",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/tasks');
	}

	public function testToggleTaskAction()
	{
		$client = static::createClient();
		$users = $this->loadFixtureFiles(['tests/DataFixtures/UserTestFixtures.yaml']);
		/** @var User $user */
		$user = $users['user_user'];

		$this->login($client, $user);

		$crawler = $client->request('GET', '/tasks');
		$form = $crawler->selectButton('Marquer comme faite')->form([

		]);
		$client->submit($form);
		$this->assertResponseRedirects('/tasks');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-success');
	}
}
