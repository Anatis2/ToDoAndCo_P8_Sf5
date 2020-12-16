<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
	/**
	 * @Route("/users", name="user_list")
	 */
	public function listAction(UserRepository $userRepository)
	{
		$users = $userRepository->findAll();

		return $this->render('user/list.html.twig', [
			'users' => $users,
		]);
    }

	/**
	 * @Route("/users/create", name="user_create")
	 */
	public function createAction(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		$userSession = $this->getUser();

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$user->setRoles(['ROLE_USER']);
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "L'utilisateur a bien été ajouté.");

			if($userSession) {
				return $this->redirectToRoute('user_list');
			} else {
				return $this->redirectToRoute('home');
			}

		}

		return $this->render('user/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/users/{id}/edit", name="user_edit")
	 */
	public function editAction(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "Votre profil a bien été modifié.");
			return $this->redirectToRoute('home');
		}

		return $this->render('user/edit.html.twig', [
			'form' => $form->createView(),
			'user' => $user,
		]);

	}

	/**
	 * @Route("/users/{id}/edit", name="userSession_edit")
	 */
	public function editSessionAction(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$userSession = $this->getUser();

		$form = $this->createForm(UserType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "Votre profil a bien été modifié.");
			return $this->redirectToRoute('home');
		}

		if($userSession == $user) {
			return $this->render('user/edit.html.twig', [
				'form' => $form->createView(),
				'userSession' => $userSession,
			]);
		} else {
			return new Response("Vous n'avez pas le droit de modifier cet utilisateur");
		}

	}

	/**
	 * @Route("/profile", name="user_profile")
	 */
	public function profile()
	{

		$userSession = $this->getUser();

		return $this->render('user/profile.html.twig', [
			'userSession' => $userSession,
		]);
	}
}
