<?php

declare(strict_types=1);

namespace App\Core\Form;

use Drago\Form\Forms;
use Drago\Form\Input;


class BaseForm extends Forms
{
	/**
	 * Adds a password input field to the form.
	 */
	public function addPasswordField(): Input
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
	public function addPasswordConfirmationField(): Input
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
	public function addEmailField(): Input
	{
		return $this->addTextInput(
			name: 'email',
			label: 'Email',
			type: 'email',
			placeholder: 'Email address',
			required: 'Please enter your email address.',
			rule: $this::Email,
		);
	}
}
