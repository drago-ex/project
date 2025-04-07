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
	 * Retrieves the latest form and ensures it's an instance of the Form class.
	 *
	 * @return Form The created form instance.
	 */
	private function getForm(): Form
	{
		$form = end($this->forms);

		// Only check the type during development (assert will be ignored in production if turned off)
		assert($form instanceof Form);

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
		$form = $this->getForm();
		$input = match ($type) {
			'password' => $form->addPassword($name, $label),
			'integer' => $form->addInteger($name, $label),
			'email' => $form->addText($name, $label)
				->setHtmlType('email'),

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
