<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        //TODO
    }

	/**
	 * @Route("/tasks/create", name="task_create")
	 */
	public function createAction(Request $request)
	{

	}

	/**
	 * @Route("/tasks/{id}/edit", name="task_edit")
	 */
	public function editAction()
	{
		//TODO
	}

	/**
	 * @Route("/tasks/{id}/toggle", name="task_toggle")
	 */
	public function toggleTaskAction()
	{
		//TODO
	}

	/**
	 * @Route("/tasks/{id}/delete", name="task_delete")
	 */
	public function deleteTaskAction()
	{
		//TODO
	}

}
