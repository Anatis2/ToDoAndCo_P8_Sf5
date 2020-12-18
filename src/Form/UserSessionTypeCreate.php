<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSessionTypeCreate extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('surname', TextType::class, [
				'label' => "Nom",
			])
			->add('firstname', TextType::class, [
				'label' => "Prénom",
			])
			->add('email', EmailType::class, [
				'label' => "Adresse email",
			])
			->add('password', RepeatedType::class, [
				'type' => PasswordType::class,
				'invalid_message' => "Les deux mots de passe doivent correspondre.",
				'required' => true,
				'first_options'  => ["label" => "Mot de passe"],
				'second_options' => ["label" => "Tapez le mot de passe à nouveau"],
			])
			->add('roles', ChoiceType::class, [
				'label' => "Rôle :",
				'multiple' => false,
				'choices' => [
					'Utilisateur' => "ROLE_USER",
					'Administrateur' => "ROLE_ADMIN",
				]
			]);
		;

		// Data transformer
		$builder->get('roles')
			->addModelTransformer(new CallbackTransformer(
				function ($rolesArray) {
					// transform the array to a string
					return count($rolesArray)? $rolesArray[0]: null;
				},
				function ($rolesString) {
					// transform the string back to an array
					return [$rolesString];
				}
			));
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}

}
