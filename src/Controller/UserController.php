<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @Route("/users", name="user_list")
	 */
	public function listAction()
	{
        //TODO
    }

	/**
	 * @Route("/users/create", name="user_create")
	 */
	public function createAction()
	{
		//TODO
	}

	/**
	 * @Route("/users/{id}/edit", name="user_edit")
	 */
	public function editAction()
	{
		//TODO
	}
}
