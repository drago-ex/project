<?php

declare(strict_types=1);

namespace App\Core\Form;

use Drago\Forms\Forms;
use Drago\Forms\Inputs;
use Nette\Forms\Form;


class BaseForm extends Forms
{
	/**
	 * Adds a password input field to the form.
	 */
	public function addPasswordField(): Inputs
	{
		return $this->addTextInput(
			name: 'password',
			label: 'Password',
			type: 'password',
			placeholder: 'Your password',
			required: 'Please enter your password.',
		);
	}


	/**
	 * Adds a password confirmation input field to the form.
	 */
	public function addPasswordConfirmationField(): Inputs
	{
		// Create a password confirmation input field
		$passwordField = $this->addTextInput(
			name: 'verify',
			label: 'Password to check',
			type: 'password',
			placeholder: 'Re-enter password',
			required: 'Please enter your password to check.',
		);

		// Check if 'password' field exists in the form
		if (!isset($this['password'])) {
			throw new \InvalidArgumentException('Password field is required for password confirmation.');
		}

		// Add the rule to check if the 'verify' field matches the 'password' field
		$passwordField->addRule($this::Equal, 'Passwords do not match.', $this['password']);

		return $passwordField;
	}


	/**
	 * Adds an email input field to the form.
	 */
	public function addEmailField(): Inputs
	{
		return $this->addTextInput(
			name: 'email',
			label: 'Email',
			type: 'email',
			placeholder: 'Email address',
			required: true,
			rule: Form::Email,
		);
	}
}
