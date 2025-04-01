<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\AssertionException;
use Nette\Utils\Random;
use Nette\Utils\Validators;
use Tracy\Debugger;


readonly class SignUpFactory
{
	public function __construct(
		private Passwords $password,
		private Factory $factory,
	) {
	}


	/**
	 * Creates the user registration form.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();

		$form->addText(SignUpData::Username, 'Username')
			->setHtmlAttribute('placeholder', 'Full name')
			->setRequired();

		$form->addText(SignUpData::Email, 'Email address')
			->setHtmlAttribute('placeholder', 'Email address')
			->setDefaultValue('@')
			->setHtmlType('email')
			->addRule($form::Email)
			->setRequired();

		$form->addPassword(SignUpData::Password, 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired();

		$form->addPassword(SignUpData::Verify, 'Password to check')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired();

		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/**
	 * Handles the successful submission of the form.
	 * Hashes the password, generates a token, and inserts the user into the database.
	 *
	 * @throws Exception
	 * @throws SignUpDuplicateEmailException
	 * @throws AssertionException
	 */
	public function success(Form $form, SignUpData $data): void
	{
		// Hash the password
		$data->password = $this->password->hash($data->password);

		// Generate a token
		$data->token = Random::generate(32);

		// Remove the password confirmation field
		$data->offsetUnset(SignUpData::Verify);

		// Validate the email format
		Validators::assert($data->email, 'email');

		try {
			// Insert the user data into the database
			Debugger::barDump($data);

		} catch (SignUpDuplicateEmailException $e) {
			// If a unique constraint violation occurs (duplicate email), throw an exception
			throw new SignUpDuplicateEmailException();
		}
	}
}
