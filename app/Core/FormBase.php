<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;


/**
 * Extends Nette's Form class to provide helper methods for quickly adding and configuring
 * text, password, and email input fields. Simplifies form creation with options for validation,
 * placeholders, and required messages. Ideal for developers who need to quickly build forms
 * with minimal configuration.
 */
class FormBase extends Form
{
	/**
	 * Generic method to add a text input field to the form.
	 * This method can be used to create text, password, or email input fields with optional validation rules.
	 *
	 * @param string $name The name of the input field.
	 * @param string $label The label for the input field.
	 * @param string|null $type The type of the input field (e.g., 'text', 'password', 'email').
	 * @param string|null $placeholder The placeholder text for the input field.
	 * @param string|null $required The error message if the field is required (optional).
	 * @param string|null $rule The validation rule (optional).
	 * @param string|null $ruleMessage The error message for the validation rule (optional).
	 * @param string|int|null $ruleValue The value for the validation rule (optional).
	 *
	 * @throws InvalidArgumentException If the input type is not supported.
	 */
	public function addTextInput(
		string $name,
		string $label,
		?string $type = 'text',
		?string $placeholder = null,
		?string $required = null,
		?string $rule = null,
		?string $ruleMessage = null,
		string|int|null $ruleValue = null,
	): TextInput
	{
		// Determine the input type and call the appropriate Nette Form method
		$input = match ($type) {
			'password' => parent::addPassword($name, $label),
			'email' => parent::addEmail($name, $label)
				->setHtmlType('email'),

			default => parent::addText($name, $label),
		};

		// Set optional attributes if provided
		if ($placeholder !== null) {
			$input->setHtmlAttribute('placeholder', $placeholder);
		}

		if ($required !== null) {
			$input->setRequired($required);
		}

		// Apply validation rule if provided
		if ($rule) {
			$input->addRule($rule, $ruleMessage, $ruleValue);
		}

		return $input;
	}


	/**
	 * Adds a password input field to the form.
	 * This method uses the `addTextInput` helper method to add a password input field
	 * with a minimum length validation rule (6 characters by default).
	 */
	public function addPasswordField(): TextInput
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
	 * This method adds a password confirmation field and automatically validates
	 * that the entered password matches the 'password' field.
	 * If the 'password' field does not exist, an exception is thrown.
	 *
	 * @throws InvalidArgumentException If no 'password' field is found in the form.
	 */
	public function addPasswordConfirmationField(): TextInput
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
			throw new InvalidArgumentException('Password field is required for password confirmation.');
		}

		// Add the rule to check if the 'verify' field matches the 'password' field
		$passwordField->addRule($this::Equal, 'Passwords do not match.', $this['password']);

		return $passwordField;
	}


	/**
	 * Adds an email input field to the form.
	 * This method uses the `addTextInput` helper method to add an email input field
	 * with an email format validation rule.
	 */
	public function addEmailField(): TextInput
	{
		return $this->addTextInput(
			name: 'email',
			label: 'Email',
			type: 'email',
			placeholder: 'Email address',
			required: 'Please enter your email address.',
		);
	}
}
