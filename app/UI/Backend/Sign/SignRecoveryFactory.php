<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use Nette\Application\UI\Form;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;


readonly class SignRecoveryFactory
{
	public function __construct(
		private Factory $factory,
		private Session $session,
	) {
	}


	private function getRecoverySection(): SessionSection
	{
		return $this->session
			->getSection('recovery');
	}


	private function setToken(): void
	{
		$session = $this->getRecoverySection();
		$session->set('token', Random::generate(6));
		$session->setExpiration('30 minutes');
	}


	private function setTokenCheck(): void
	{
		$this->getRecoverySection()
			->set('tokenCheck', true);
	}


	private function removeToken(): void
	{
		$this->getRecoverySection()
			->remove(['token', 'tokenCheck']);
	}


	public function getToken(): SignRecoveryToken
	{
		$session = $this->getRecoverySection();
		return new SignRecoveryToken(
			token: $session->get('token'),
			tokenCheck: $session->get('tokenCheck'),
		);
	}


	public function createRequest(): Form
	{
		$form = $this->factory->create();
		$form->addText('email', 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::Email, 'Please enter a valid email address.')
			->setRequired('Please enter your email address.');

		$form->addSubmit('send', 'Reset password');
		$form->onSuccess[] = $this->request(...);
		return $form;
	}


	public function createCheckToken(): Form
	{
		$form = $this->factory->create();
		$form->addText('token', 'Token check')
			->setHtmlAttribute('placeholder', 'Enter a token to check')
			->addRule($form::Equal, 'Token check failed.', $this->getToken()->token)
			->setRequired('Please enter the token from the email.');

		$form->addSubmit('send', 'Token check');
		$form->onSuccess[] = $this->checkToken(...);
		return $form;
	}


	public function creatChangePassword(): Form
	{
		$form = $this->factory->create();
		$form->addPassword(SignUpData::Password, 'New password')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::MinLength, 'Password must be at least %d characters long.', 6)
			->setRequired('Please enter your password.');

		$form->addPassword(SignUpData::Verify, 'Password to check')
			->setHtmlAttribute('placeholder', 'Your password')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired('Please enter your password to check.');

		$form->addSubmit('send', 'Change password');
		$form->onSuccess[] = $this->changePassword(...);
		return $form;
	}


	public function request(Form $form): void
	{
		$this->setToken();
	}


	public function checkToken(Form $form): void
	{
		$this->setTokenCheck();
	}


	public function changePassword(): void
	{
		$this->removeToken();
	}
}
