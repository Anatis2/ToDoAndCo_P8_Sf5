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
			'all' => true,
		]);
	}

	/**
     * @Route("/tasksTodo", name="taskTodo_list")
     */
    public function listTodoAction(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findBy(['isDone' => false]);

        return $this->render('task/list.html.twig', [
        	'tasks' => $tasks,
			'todo' => true,
		]);
    }

	/**
	 * @Route("/tasksDone", name="taskDone_list")
	 */
	public function listDoneAction(TaskRepository $taskRepository)
	{
		$tasks = $taskRepository->findBy(['isDone' => true]);

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

		$user = $this->getUser();

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$task->setUser($user);
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
	public function editAction(Request $request, Task $task, EntityManagerInterface $em)
	{
		$form = $this->createForm(TaskType::class, $task);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$em->persist($task);
			$em->flush();

			$this->addFlash('success', 'La tâche a été bien été modifiée.');
			return $this->redirectToRoute('task_list');
		}

		return $this->render('task/edit.html.twig', [
			'form' => $form->createView(),
			'task' => $task,
		]);
	}

	/**
	 * @Route("/tasks/{id}/toggle", name="task_toggle")
	 */
	public function toggleTaskAction(Task $task, EntityManagerInterface $em)
	{
		$task->toggle(!$task->isDone());
		$em->flush();

		if($task->isDone()) {
			$this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
		} else {
			$this->addFlash('success', sprintf('La tâche %s a bien été rétrogradée comme non faite.', $task->getTitle()));
		}

		return $this->redirectToRoute('task_list');

	}

	/**
	 * @Route("/tasks/{id}/delete", name="task_delete")
	 */
	public function deleteTaskAction(Request $request, EntityManagerInterface $em, Task $task)
	{
		if($this->isCsrfTokenValid('delete' . $task->getId(), $request->get('_token'))) {
			$em->remove($task);
			$em->flush();

			$this->addFlash('success', 'La tâche a bien été supprimée.');
			return $this->redirectToRoute('task_list');
		}

		return new Response("Il y a eu un problème lors de la suppression de la tâche");
	}

}
