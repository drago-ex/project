<?php

declare(strict_types=1);

namespace App\Core;

use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use Nette\Security\User;
use Stringable;


/**
 * Factory class to create forms with optional protection based on user login status.
 */
readonly class Factory
{
	/**
	 * Constructor to initialize dependencies.
	 *
	 * @param Translator $translator Translator for form translation.
	 * @param User $user User object to check login status.
	 */
	public function __construct(
		private Translator $translator,
		private User $user,
	) {
	}


	/**
	 * Creates and returns a form instance.
	 *
	 * If the user is logged in, adds protection to the form.
	 * Sets the translator for form elements.
	 *
	 * @return Form The created form instance.
	 */
	public function create(): Form
	{
		$form = new Form();

		// Add form protection if the user is logged in
		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}

		// Set the translator for form
		$form->setTranslator($this->translator);

		return $form;
	}


	public function addReadyEmail(form $form): Form
	{
		$form->addText('email', 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::Email, 'Please enter a valid email address.')
			->setRequired('Please enter your email address.');

		return $form;
	}


	public function addReadyPassword(Form $form): Form
	{
		$form->addPassword('password', 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired('Please enter your password.');

		return $form;
	}


	public function addReadyPasswordCheck(Form $form, string $checkValue): Form
	{
		$form->addPassword('verify', 'Password to check')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::Equal, 'Passwords do not match.', $checkValue)
			->setRequired('Please enter your password to check.');

		return $form;
	}
}
