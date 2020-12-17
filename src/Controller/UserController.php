<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSessionType;
use App\Form\UserSessionTypeCreate;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
	/**
	 * @Route("/users", name="user_list")
	 *  @isGranted("ROLE_ADMIN")
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
	 * @isGranted("ROLE_ADMIN")
	 */
	public function createAction(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$user = new User();
		$form = $this->createForm(UserSessionTypeCreate::class, $user);

		$userSession = $this->getUser();

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$user->setRoles(['ROLE_USER']);
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "L'utilisateur a bien été ajouté. Un email vient de lui être envoyé pour qu'il active son compte.");

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

			$this->addFlash('success', "L'utilisateur a bien été modifié.");
			return $this->redirectToRoute('home');
		}

		return $this->render('user/edit.html.twig', [
			'form' => $form->createView(),
			'user' => $user,
		]);

	}

	/**
	 * @Route("/users/{id}/delete", name="user_delete")
	 * @isGranted("ROLE_ADMIN")
	 */
	public function deleteAction(Request $request, EntityManagerInterface $em, User $user)
	{
		if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
			$em->remove($user);
			$em->flush();
			$this->addFlash('success', "L'utilisateur a bien été supprimé");
			return $this->redirectToRoute('user_list');
		}
		return new Response("Il y a eu un problème lors de la suppression de l'utlisateur.");
	}

	/**
	 * @Route("/userSession/{id}/edit", name="userSession_edit")
	 */
	public function editSessionAction(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$userSession = $this->getUser();

		$form = $this->createForm(UserSessionType::class, $user);

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
			return $this->render('user/editSession.html.twig', [
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
