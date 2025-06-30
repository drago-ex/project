<?php

declare(strict_types=1);

namespace App\Core\Form;

use Nette\Localization\Translator;
use Nette\Security\User;


/**
 * Factory class for creating instances of FormBase with necessary configurations.
 */
readonly class Factory
{
	public function __construct(
		private Translator $translator,
		private User $user,
	) {
	}


	public function create(): Forms
	{
		$form = new Forms;

		// Add form protection if the user is logged in
		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}

		// Set the translator for form
		$form->setTranslator($this->translator);

		return $form;
	}
}
