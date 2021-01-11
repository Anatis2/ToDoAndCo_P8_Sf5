<?php

namespace App\Tests\Traits;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

trait NeedLogin
{
	public function login(KernelBrowser $client, User $user)
	{
		// On récupère la session en cours
		$session = $client->getContainer()->get('session');
		// On génère un UsernamePasswordToken à partir de notre $user
		$token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
		/* Dans la session, on créé une clé avec ce qu'attend le firewall (dans notre cas _security_main) avec,
		   en 2ème param, qqch de type UsernamePasswordToken donc notre $token en version sérialisée)*/
		$session->set('_security_main', serialize($token));
		// On sauvegarde cette session
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$client->getCookieJar()->set($cookie);
	}
}
