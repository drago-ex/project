<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use Nette\Application\UI\Form;
use Nette\Forms\Controls\TextInput;
use Nette\Localization\Translator;
use Nette\Security\User;


/**
 * Factory class to create forms with optional protection based on user login status.
 *
 * This class provides methods for creating and adding various form inputs (text, password, email, etc.)
 * with optional validation rules and messages.
 */
class Factory
{
	private array $forms = [];


	/**
	 * Constructor to initialize dependencies.
	 *
	 * @param Translator $translator Translator for form translation.
	 * @param User $user User object to check login status.
	 */
	public function __construct(
		private readonly Translator $translator,
		private readonly User $user,
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
		$this->forms[] = $form;

		return $form;
	}


	/**
	 * Generic method to add a text input field to the form.
	 * This method can be used to create text, password, or email input fields with optional validation rules.
	 *
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
		$form = end($this->forms);
		$input = match ($type) {
			'password' => $form->addPassword($name, $label),
			'integer' => $form->addInteger($name, $label),
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


	/**
	 * Adds a password input field to the form.
	 * This method uses the `addTextInput` helper method to add a password input field
	 * with a minimum length validation rule (6 characters by default).
	 *
	 * @return TextInput The created password input field.
	 */
	public function addPassword(): TextInput
	{
		$form = end($this->forms);
		return $this->addTextInput(
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
	 * This method adds a password verification field and automatically validates
	 * that the entered password matches the 'password' field.
	 * If the 'password' field does not exist, an exception is thrown.
	 *
	 * @return TextInput The created password verification input field.
	 * @throws InvalidArgumentException If no 'password' field is found in the form.
	 */
	public function addPasswordVerification(): TextInput
	{
		$form = end($this->forms);

		// Add the verification password input field
		$passwordField = $this->addTextInput(
			name: 'verify',
			label: 'Password to check',
			type: 'password',
			placeholder: 'Please re-enter your password',
			required: 'Please enter the password to check.',
		);

		// Check if 'password' field exists in the form
		if (!isset($form['password'])) {
			throw new InvalidArgumentException('Password field is required for password verification.');
		}

		// Add the rule to check if the 'verify' field matches the 'password' field
		$passwordField->addRule($form::Equal, 'Passwords do not match.');

		return $passwordField;
	}


	/**
	 * Adds an email input field to the form.
	 * This method uses the `addTextInput` helper method to add an email input field
	 * with an email format validation rule.
	 *
	 * @return TextInput The created email input field.
	 */
	public function addEmail(): TextInput
	{
		$form = end($this->forms);
		return $this->addTextInput(
			name: 'email',
			label: 'Email',
			type: 'email',
			placeholder: 'Your email address',
			required: 'Please enter your email address.',
			rule: $form::Email,
			ruleMessage: 'Please enter a valid email address.',
		);
	}
}
