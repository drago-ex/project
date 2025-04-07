<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Nette\Application\UI\Form;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;
use Tracy\Debugger;


readonly class SignRecoveryFactory
{
	public function __construct(
		private Factory $factory,
		private Session $session,
		private Connection $connection,
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
		$form->addText('token', 'Code')
			->setHtmlAttribute('placeholder', 'Enter the code from the email')
			->addRule($form::Equal, 'The code entered is invalid.', $this->getToken()->token)
			->setRequired('Please enter the code from the email.');

		$form->addSubmit('send', 'Continue password recovery');
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
			->setHtmlAttribute('placeholder', 'Re-enter password')
			->addRule($form::Equal, 'Passwords do not match.', $form['password'])
			->setRequired('Please enter your password to check.');

		$form->addSubmit('send', 'Change your password');
		$form->onSuccess[] = $this->changePassword(...);
		return $form;
	}


	public function request(Form $form): void
	{
		$findEmail = $this->connection
			->select('email')
			->from(UsersEntity::Table)
			->where('email = ?', $form->getValues()['email'])
			->fetch();

		if ($findEmail) {
			$this->setToken();
			Debugger::barDump($this->getToken());

		} else {
			$form->addError("We're sorry, but we don't know such an email address.");
		}
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
