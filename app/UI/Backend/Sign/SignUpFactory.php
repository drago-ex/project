<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Dibi\UniqueConstraintViolationException;
use Exception;
use Nette\Application\UI\Form;
use Nette\Security\Passwords;
use Nette\Utils\AssertionException;
use Nette\Utils\Random;
use Nette\Utils\Validators;


readonly class SignUpFactory
{
	public function __construct(
		private Passwords $password,
		private Factory $factory,
		private Connection $connection,
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
			->setRequired('Please enter your full name.');

		$form->addEmailField()
			->setDefaultValue('@');

		$form->addPasswordField()
			->setHtmlAttribute('autocomplete', 'new-password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 8)
			->addRule(
				$form::Pattern,
				'The password must contain uppercase and lowercase letters, numbers, and a special character.',
				'^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[^A-Za-z0-9])[\S]{8,}$',
			);

		$form->addPasswordConfirmationField()
			->setHtmlAttribute('autocomplete', 'new-password');

		$form->addSubmit('send', 'Sign up');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/**
	 * Handles the successful submission of the form.
	 * Hashes the password, generates a token, and inserts the user into the database.
	 *
	 * @throws Exception
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
			$this->connection->insert(UsersEntity::Table, $data->toArray())
				->execute();

		} catch (UniqueConstraintViolationException $e) {
			$message = match ($e->getCode()) {
				1062 => "We're sorry, but an account with this email address already exists.",
				default => 'Unknown status code.',
			};
			$form->addError($message);
		}
	}
}
