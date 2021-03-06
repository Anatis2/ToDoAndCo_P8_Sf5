<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserCreateType;
use App\Form\UserPasswordEditType;
use App\Form\UserRoleEditType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
	/**
	 * @Route("/users", name="user_list")
	 * @isGranted("ROLE_ADMIN")
	 */
	public function userList(UserRepository $userRepository)
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
	public function userCreate(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mailer)
	{
		$user = new User();
		$form = $this->createForm(UserCreateType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = bin2hex(random_bytes(10));
			$encodedPassword = $encoder->encodePassword($user, $password);
			$user->setPassword($encodedPassword);
			$user->setRoles(['ROLE_USER']);
			$em->persist($user);
			$em->flush();
			$email = (new TemplatedEmail())
				->from('claire.coubard@gmail.com')
				->to($user->getEmail())
				->subject('Activation de votre compte TODO AND CO !')
				->htmlTemplate('user/mailCreationCompte.html.twig')
				->context([
					'userEmail' => $user->getEmail(),
					'userPwd' => $password,
				]);
			$mailer->send($email);

			$this->addFlash('success', "L'utilisateur a bien été ajouté. Un email vient de lui être envoyé pour le notifier.");
			return $this->redirectToRoute('user_list');
		}

		return $this->render('user/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/users/{id}/editRole", name="userRole_edit")
	 * @isGranted("ROLE_ADMIN")
	 */
	public function userEditRole(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$form = $this->createForm(UserRoleEditType::class, $user);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$password = $encoder->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$em->persist($user);
			$em->flush();

			$this->addFlash('success', "L'utilisateur a bien été modifié.");
			return $this->redirectToRoute('user_list');
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
	public function userDelete(Request $request, EntityManagerInterface $em, User $user)
	{
		if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
			$em->remove($user);
			$em->flush();
			$this->addFlash('success', "L'utilisateur a bien été supprimé");
			return $this->redirectToRoute('user_list');
		}
		return new Response("Il y a eu un problème lors de la suppression de l'utilisateur.");
	}

	/**
	 * @Route("/user/{id}/edit", name="user_edit")
	 * @isGranted("ROLE_USER")
	 */
	public function userEdit(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$userSession = $this->getUser();

		$form = $this->createForm(UserEditType::class, $user);

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
	 * @Route("/user/{id}/editPassword", name="userPassword_edit")
	 * @isGranted("ROLE_USER")
	 */
	public function userEditPassword(Request $request, User $user, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
	{
		$userSession = $this->getUser();

		$form = $this->createForm(UserPasswordEditType::class, $user);

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
	 * @isGranted("ROLE_USER")
	 */
	public function profile()
	{
		$userSession = $this->getUser();

		return $this->render('user/profile.html.twig', [
			'userSession' => $userSession,
		]);
	}
}
