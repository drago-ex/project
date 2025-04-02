<?php

declare(strict_types=1);

namespace App\UI\Backend\Sign;

use App\Core\Factory;
use Nette\Application\UI\Form;


readonly class SignRecoveryFactory
{
	public function __construct(
		private Factory $factory,
	) {
	}


	/**
	 * Create recovery lost password.
	 */
	public function create(): Form
	{
		$form = $this->factory->create();
		$form->addText('email', 'Email')
			->setHtmlAttribute('email')
			->setHtmlAttribute('placeholder', 'Email address')
			->addRule($form::Email, 'Please enter a valid email address.')
			->setRequired('Please enter your email address.');

		$form->addSubmit('send', 'Reset password');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	public function success(Form $form)
	{

	}
}
