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
		$form->addText(SignData::Email, 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::Email, 'Please enter a valid email address.')
			->setRequired('Please enter your email address.');

		$form->addPassword(SignData::Password, 'Password')
			->setHtmlAttribute('placeholder', 'Your password')
			->setRequired('Please enter your password.');

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


	/**
	 * Create recovery lost password.
	 */
	protected function createComponentSignRecovery(): Form
	{
		$form = $this->signRecoveryFactory->create();
		$form->onSuccess[] = function () {
			//$this->flashMessage('Your recovery request has been successfully sent.', Alert::Info);
			//$this->redirect('in');
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
