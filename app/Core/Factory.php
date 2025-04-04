<?php

declare(strict_types=1);

namespace App\Core;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;
use Nette\Localization\Translator;
use Nette\Security\User;

/**
 * Factory class to create forms with optional protection based on user login status.
 * This class provides methods for creating and adding various form inputs (text, password, email, etc.)
 * with optional validation rules and messages.
 */
readonly class Factory
{
	/**
	 * Constructor to initialize dependencies.
	 *
	 * @param Translator $translator Translator for form translation.
	 * @param User $user User object to check login status.
	 */
	public function __construct(
		private Translator $translator,
		private User $user,
	) {
	}


	/**
	 * Creates and returns a form instance.
	 *
	 * If the user is logged in, adds protection to the form.
	 * Sets the translator for form elements.
	 *
	 * @return Form The created form instance.
	 */
	public function create(): Form
	{
		$form = new Form();

		// Add form protection if the user is logged in
		if ($this->user->isLoggedIn()) {
			$form->addProtection();
		}

		// Set the translator for form
		$form->setTranslator($this->translator);

		return $form;
	}


	/**
	 * Adds a password input field to the form.
	 * This method uses the `addTextInput` helper method to add a password input field
	 * with a minimum length validation rule (6 characters by default).
	 *
	 * @param Form $form The form to add the password input field to.
	 * @return TextInput The created password input field.
	 */
	public function addPassword(Form $form): TextInput
	{
		return $this->addTextInput(
			$form,
			name: 'password',
			label: 'Password',
			type: 'password',
			placeholder: 'Your password',
			required: 'Please enter your password.',
			rule: $form::MinLength,
			ruleMessage: 'Password must be at least %d characters long.',
			ruleValue: 6,
		);
	}


	/**
	 * Adds a password verification input field to the form.
	 * This method uses the `addTextInput` helper method to add a second password input field
	 * and validates if the entered passwords match.
	 *
	 * @param Form $form The form to add the password verification input field to.
	 * @param string $ruleValue The value of the password input field to check against.
	 * @return TextInput The created password verification input field.
	 */
	public function addPasswordVerification(Form $form, string $ruleValue): TextInput
	{
		$passwordField = $this->addTextInput(
			$form,
			name: 'verify',
			label: 'Password to check',
			type: 'password',
			placeholder: 'Your password to check',
			required: 'Please enter the password to check.',
		);

		$passwordField->addRule($form::Equal, 'Passwords do not match.', $ruleValue);
		return $passwordField;
	}


	/**
	 * Adds an email input field to the form.
	 * This method uses the `addTextInput` helper method to add an email input field
	 * with an email format validation rule.
	 *
	 * @param Form $form The form to add the email input field to.
	 * @return TextInput The created email input field.
	 */
	public function addEmail(Form $form): TextInput
	{
		return $this->addTextInput(
			$form,
			name: 'email',
			label: 'Email',
			type: 'email',
			placeholder: 'Your email address',
			required: 'Please enter your email address.',
			rule: $form::Email,
			ruleMessage: 'Please enter a valid email address.',
		);
	}


	/**
	 * Generic method to add a text input field to the form.
	 * This method can be used to create text, password, or email input fields with optional validation rules.
	 *
	 * @param Form $form The form to add the input field to.
	 * @param string $name The name of the input field.
	 * @param string $label The label for the input field.
	 * @param string|null $type The type of the input field (e.g., 'text', 'password', 'email').
	 * @param string|null $placeholder The placeholder text for the input field.
	 * @param string|null $required The error message if the field is required.
	 * @param string|null $rule The validation rule (optional).
	 * @param string|null $ruleMessage The error message for the validation rule (optional).
	 * @param string|int|null $ruleValue The value for the validation rule (optional).
	 *
	 * @return TextInput The created input field.
	 */
	public function addTextInput(
		Form $form,
		string $name,
		string $label,
		?string $type = 'text',
		?string $placeholder = null,
		?string $required = null,
		?string $rule = null,
		?string $ruleMessage = null,
		string|int|null $ruleValue = null,
	): TextInput {
		$input = match ($type) {
			'password' => $form->addPassword($name, $label),
			'email' => $form->addText($name, $label)
				->setHtmlAttribute('type', 'email'),

			default => $form->addText($name, $label),
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
}
