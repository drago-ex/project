<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Form\Factory;
use App\UI\Presenter;
use Drago\Application\UI\Alert;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Neon\Exception;
use Nette\Security\AuthenticationException;
use Tracy\Debugger;


/**
 * Handles user authentication and registration.
 * Includes pages for sign-in, sign-up, and password recovery.
 *
 * @property SignTemplate $template
 */
final class SignPresenter extends Presenter
{
	#[Persistent]
	public string $backlink = '';


	public function __construct(
		private readonly Factory $factory,
		private readonly SignUpFactory $signUpFactory,
		private readonly SignRecoveryFactory $signRecoveryFactory,
		private readonly SignRecoverySession $signRecoverySession,
	) {
		parent::__construct();
	}


	/**
	 * Redraws specific parts of the page (title and body).
	 * Used to update the UI during AJAX requests.
	 */
	private function redrawSnippets(): void
	{
		$this->redrawControl('title');
		$this->redrawControl('body');
	}


	/**
	 * Called before rendering the template.
	 * Sets the recovery token if the current action is 'recovery'.
	 * If the request is AJAX, redraw snippets to update page parts dynamically.
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		if ($this->getAction() === 'recovery') {
			$this->template->signRecoveryToken = $this->signRecoverySession->createSignRecoveryToken();
		}

		if ($this->isAjax()) {
			$this->redrawSnippets();
		}
	}


	/**
	 * Creates and handles the sign-in form.
	 */
	protected function createComponentSignIn(): Form
	{
		$form = $this->factory->create();
		$form->addEmailField();
		$form->addPasswordField()
			->setAutocomplete('current-password');

		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/**
	 * Handles sign-in form success.
	 * Logs the user in and redirect to the admin page.
	 */
	public function success(Form $form, SignData $data): void
	{
		try {
			$this->getUser()->login($data->email, $data->password);
			$this->restoreRequest($this->backlink);
			$this->redirect(':Backend:Admin:');
		} catch (AuthenticationException $e) {
			$messages = [
				1 => 'User not found.',
				2 => 'The password is incorrect.',
			];
			$form->addError($messages[$e->getCode()] ?? 'Unknown error occurred.');
		}
	}


	/**
	 * Creates and handles the sign-up form.
	 */
	protected function createComponentSignUp(): Form
	{
		$form = $this->signUpFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Your registration has been successfully completed, you can now log in.', Alert::Success);
			$this->redirect('in');
		};
		return $form;
	}


	/**
	 * Creates and handles the password recovery request form.
	 * @throws Exception
	 */
	protected function createComponentSignRecoveryRequest(): Form
	{
		$factory = $this->signRecoveryFactory;
		$factory->translator = $this->getTranslator();

		$form = $factory->createRequest();
		$form->onSuccess[] = function () {
			$this->flashMessage('A password recovery code has been sent to your email.');
		};

		$form->onError[] = function (Form $form) {
			foreach ($form->getErrors() as $error) {
				Debugger::barDump($error);
			}
		};
		return $form;
	}


	/**
	 * Creates and handles the token check form for password recovery.
	 */
	protected function createComponentSignRecoveryCheckToken(): Form
	{
		$form = $this->signRecoveryFactory->createCheckToken();
		$form->onSuccess[] = function () {
			$this->flashMessage('Code check was successful.', Alert::Success);
		};
		return $form;
	}


	/**
	 * Creates and handles the password change form.
	 */
	protected function createComponentSignRecoveryChangePassword(): Form
	{
		$form = $this->signRecoveryFactory->createChangePassword();
		$form->onSuccess[] = function () {
			$this->flashMessage('Password change was successful', Alert::Success);
			$this->redirect('in');
		};
		return $form;
	}


	/**
	 * Logs out the current user.
	 * After logout, the presenter will render the 'out' view or perform the default rendering.
	 */
	public function actionOut(): void
	{
		$this->getUser()->logout();
	}
}
