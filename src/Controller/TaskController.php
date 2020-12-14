<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task/list.html.twig', [
        	'tasks' => $tasks,
		]);
    }

	/**
	 * @Route("/tasks/create", name="task_create")
	 */
	public function createAction(Request $request, EntityManagerInterface $em)
	{
		$task = new Task();
		$form = $this->createForm(TaskType::class, $task);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$em->persist($task);
			$em->flush();

			$this->addFlash('success', 'La tâche a été bien été ajoutée.');

			return $this->redirectToRoute('task_list');
		}

		return $this->render('task/create.html.twig', [
			'form' => $form->createView()
		]);
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
