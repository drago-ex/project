<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\UI\Presenter;
use Drago\Application\UI\Alert;
use Nette\Application\AbortException;
use Nette\Application\Attributes\Persistent;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;


/**
 * Sign-in user.
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
	) {
		parent::__construct();
	}


	private function redraw(): void
	{
		$this->redrawControl('title');
		$this->redrawControl('body');
	}


	public function renderIn(): void
	{
		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	public function renderUp(): void
	{
		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	public function renderRecovery(): void
	{
		$token = $this->signRecoveryFactory->getToken();
		$this->template->signRecoveryToken = $token;

		if ($this->isAjax()) {
			$this->redraw();
		}
	}


	/**
	 * Create the sign-in form.
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
	 * Handle form submission success.
	 * @throws AbortException
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
	 * Create the sign-up form.
	 */
	protected function createComponentSignUp(): Form
	{
		$form = $this->signUpFactory->create();
		$form->onSuccess[] = function () {
			$this->flashMessage('Your registration has been successfully completed, you can now log in.', Alert::Info);
			$this->redirect('in');
		};
		return $form;
	}


	protected function createComponentSignRecoveryRequest(): Form
	{
		$form = $this->signRecoveryFactory->createRequest();
		$form->onSuccess[] = function () {
			$this->flashMessage('A password recovery code has been sent to your email.', Alert::Info);
		};
		return $form;
	}


	protected function createComponentSignRecoveryCheckToken(): Form
	{
		$form = $this->signRecoveryFactory->createCheckToken();
		$form->onSuccess[] = function () {
			$this->flashMessage('Code check was successful.', Alert::Info);
		};
		return $form;
	}


	protected function createComponentSignRecoveryChangePassword(): Form
	{
		$form = $this->signRecoveryFactory->creatChangePassword();
		$form->onSuccess[] = function () {
			$this->flashMessage('Password change was successful.', Alert::Info);
		};
		return $form;
	}


	/**
	 * Logout user from application.
	 * @throws AbortException
	 */
	public function actionOut(): void
	{
		$this->getUser()->logout();
		if ($this->isAjax()) {
			$this->redraw();
		}
	}
}
