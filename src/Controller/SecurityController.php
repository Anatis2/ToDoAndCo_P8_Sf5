<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="login")
	 */
	public function loginAction()
	{
		//TODO
	}

	/**
	 * @Route("/login_check", name="login_check")
	 */
	public function loginCheck()
	{
		//TODO
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logoutCheck()
	{
		//TODO
	}
}
