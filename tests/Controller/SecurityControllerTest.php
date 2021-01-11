<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
	public function testDisplayLogin()
	{
		$client = static::createClient();
		$client->request('GET', '/login');
		$this->assertResponseStatusCodeSame(Response::HTTP_OK);
	}

	public function testLoginOK()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/login');
		$form = $crawler->selectButton('Se connecter')->form([
			'email' => "user@user.fr",
			'password' => "user",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/');
	}

	public function testLoginNOK()
	{
		$client = static::createClient();
		$crawler = $client->request('GET', '/login');
		$form = $crawler->selectButton('Se connecter')->form([
			'email' => "user@user.fr",
			'password' => "fakePassword",
		]);
		$client->submit($form);
		$this->assertResponseRedirects('/login');
		$client->followRedirect();
		$this->assertSelectorExists('.alert-danger');
	}

}