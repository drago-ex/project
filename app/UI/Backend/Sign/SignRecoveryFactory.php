<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use App\Core\User\UsersEntity;
use Dibi\Connection;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;
use Nette\Http\Session;
use Nette\Http\SessionSection;
use Nette\Utils\Random;


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
		$this->factory->addEmail();
		$form->addSubmit('send', 'Reset password');
		$form->onSuccess[] = $this->request(...);
		return $form;
	}


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


	public function tokenCheck(TextInput $input): bool
	{
		return $input->getValue() === $this->getToken()->token;
	}


	public function creatChangePassword(): Form
	{
		$form = $this->factory->create();
		$this->factory->addPassword();
		$this->factory->addPasswordVerification();
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
