<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Exception\EmailNotFoundException;
use App\Core\Form\Factory;
use Dibi\Exception;
use Drago\Attr\AttributeDetectionException;
use Drago\Form\Autocomplete;
use Drago\Localization\Translator;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;


/**
 * Factory for creating password recovery forms and handling password recovery logic.
 * Provides methods for creating forms related to password recovery: request form, token check, and password change.
 */
class SignRecoveryFactory
{
	public Translator $translator;


	public function __construct(
		private readonly Factory $factory,
		private readonly SignRecoverySession $signRecoverySession,
		private readonly SignRepository $signRepository,
		private readonly SignSender $signSender,
	) {
	}


	/**
	 * Creates the password recovery request form.
	 */
	public function createRequest(): Form
	{
		$form = $this->factory->create();
		$form->addEmailField();
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
		$form->addTextInput(
			name: 'token',
			label: 'Code',
			placeholder: 'Enter the code from the email',
			required: 'Please enter the code from the email.',
		)->addRule([$this, 'tokenCheck'], 'The code entered is invalid.');

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
	public function createChangePassword(): Form
	{
		$form = $this->factory->create();
		$form->addPasswordField()
			->setAutocomplete(Autocomplete::NewPassword);

		$form->addPasswordConfirmationField()
			->setAutocomplete(Autocomplete::NewPassword);

		$form->addSubmit('send', 'Change your password');
		$form->onSuccess[] = $this->changePassword(...);
		return $form;
	}


	/**
	 * Handles the password recovery request form submission.
	 * Generates a recovery token if the email exists in the database.
	 *
	 * @throws Exception
	 * @throws AttributeDetectionException
	 */
	public function request(Form $form): void
	{
		try {
			$values = $form->getValues();
			$email = $values['email'];

			// We will verify if the user exists by email.
			$this->signRepository->findUserByEmail($email);

			// We will create a token and save the email.
			$this->signRecoverySession->generateToken($email);

			// We will create a sending email.
			$request = $this->signSender;
			$request->email = $email;
			$request->token = $this->signRecoverySession->getToken();
			$request->setTranslator($this->translator);
			$request->sendEmail();


		} catch (EmailNotFoundException $e) {
			if ($e->getCode()) {
				$message = match ($e->getCode()) {
					1 => "We're sorry, but we don't know such an email address.",
					default => 'Unknown status code.',
				};
				$form->addError($message);
			}
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
	public function changePassword(Form $form): void
	{
		try {
			$password = $form->getValues()['password'];
			$email = $this->signRecoverySession->getEmail();
			$this->signRepository->updatePassword($email, $password);

			// We delete the token and the control flag.
			$this->signRecoverySession->removeToken();

		} catch (\Throwable $e) {
			$form->addError('An error occurred during password change.');
		}
	}
}
