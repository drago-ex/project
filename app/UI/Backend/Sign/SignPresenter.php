<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\UI\Presenter;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


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
	 * Redraws specific parts of the page.
	 */
	private function redraw(): void
	{
		$this->redrawControl('title');
		$this->redrawControl('body');
	}


	/**
	 * Renders the sign-in page.
	 */
	public function renderIn(): void
	{
		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	/**
	 * Renders the sign-up page.
	 */
	public function renderUp(): void
	{
		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	/**
	 * Renders the password recovery page.
	 * Sets the recovery token in the template.
	 */
	public function renderRecovery(): void
	{
		$this->template->signRecoveryToken = $this->signRecoverySession
			->createSignRecoveryToken();

		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	/**
	 * Creates and handles the sign-in form.
	 */
	protected function createComponentSignIn(): Form
	{
		$form = $this->factory->create();
		$this->factory->addEmail();
		$this->factory->addPassword();
		$form->addSubmit('send', 'Sign in');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	/**
	 * Handles sign-in form success.
	 * Logs the user in and redirects to the admin page.
	 */
	public function success(Form $form, SignData $data): void
	{
		try {
			$this->getUser()->login($data->email, $data->password);
			$this->restoreRequest($this->backlink);
			$this->redirect(':Backend:Admin:');
		} catch (AuthenticationException $e) {
			$message = match ($e->getCode()) {
				1 => 'User not found.',
				2 => 'The password is incorrect.',
				default => 'Unknown error occurred.',
			};
			$form->addError($message);
		}
	}


	/**
	 * Creates and handles the sign-up form.
	 */
	protected function createComponentSignUp(): Form
	{
		$form = $this->signUpFactory->create();
		$this->addSuccessCallback(
			$form,
			'Your registration has been successfully completed, you can now log in.',
			function () {
				$this->redirect('in');
			},
		);
		return $form;
	}


	/**
	 * Creates and handles the password recovery request form.
	 */
	protected function createComponentSignRecoveryRequest(): Form
	{
		$form = $this->signRecoveryFactory->createRequest();
		$this->addSuccessCallback($form, 'A password recovery code has been sent to your email.');
		return $form;
	}


	/**
	 * Creates and handles the token check form for password recovery.
	 */
	protected function createComponentSignRecoveryCheckToken(): Form
	{
		$form = $this->signRecoveryFactory->createCheckToken();
		$this->addSuccessCallback($form, 'Code check was successful.');
		return $form;
	}


	/**
	 * Creates and handles the password change form.
	 */
	protected function createComponentSignRecoveryChangePassword(): Form
	{
		$form = $this->signRecoveryFactory->creatChangePassword();
		$this->addSuccessCallback(
			$form,
			'Password change was successful.',
			function () {
				$this->redirect('in');
			},
		);
		return $form;
	}


	/**
	 * Logs the user out.
	 */
	public function actionOut(): void
	{
		$this->getUser()->logout();
		if ($this->isAjax()) {
			$this->redraw();
		}
	}
}
