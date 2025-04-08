<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;


/**
 * Factory for creating password recovery forms and handling password recovery logic.
 * Provides methods for creating forms related to password recovery: request form, token check, and password change.
 */
readonly class SignRecoveryFactory
{
	public function __construct(
		private Factory $factory,
		private Connection $connection,
		private SignRecoverySession $signRecoverySession,
	) {
	}


	/**
	 * Creates the password recovery request form.
	 */
	public function createRequest(): Form
	{
		$form = $this->factory->create();
		$this->factory->addEmail();
		$form->addSubmit('send', 'Reset password');
		$form->onSuccess[] = $this->request(...);
		return $form;
	}


	/**
	 * Creates the form for checking the recovery token.
	 */
	public function createCheckToken(): Form
	{
		$form = $this->factory->create();
		$form->addText('token', 'Code')
			->setHtmlAttribute('placeholder', 'Enter the code from the email')
			->setRequired('Please enter the code from the email.')
			->addRule([$this, 'tokenCheck'], 'The code entered is invalid.');

		$form->addSubmit('send', 'Continue password recovery');
		$form->onSuccess[] = $this->checkToken(...);
		return $form;
	}


	/**
	 * Checks if the entered token is valid.
	 *
	 * @param TextInput $input The input field for the token.
	 * @return bool True if the token is valid, false otherwise.
	 */
	public function tokenCheck(TextInput $input): bool
	{
		return $this->signRecoverySession
			->isTokenValid($input->getValue());
	}


	/**
	 * Creates the form for changing the password.
	 */
	public function creatChangePassword(): Form
	{
		$form = $this->factory->create();
		$this->factory->addPassword();
		$this->factory->addPasswordVerification();
		$form->addSubmit('send', 'Change your password');
		$form->onSuccess[] = $this->changePassword(...);
		return $form;
	}


	/**
	 * Handles the password recovery request form submission.
	 * Generates a recovery token if the email exists in the database.
	 */
	public function request(Form $form): void
	{
		$findEmail = $this->connection
			->select('email')
			->from(UsersEntity::Table)
			->where('email = ?', $form->getValues()['email'])
			->fetch();

		if ($findEmail) {
			$this->signRecoverySession
				->setToken();
		} else {
			$form->addError("We're sorry, but we don't know such an email address.");
		}
	}


	/**
	 * Handles the token check form submission.
	 */
	public function checkToken(): void
	{
		$this->signRecoverySession
			->setTokenCheck();
	}


	/**
	 * Handles the password change form submission.
	 * Removes the token from the session after the password is successfully changed.
	 */
	public function changePassword(): void
	{
		$this->signRecoverySession
			->removeToken();
	}
}
