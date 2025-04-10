<?php

declare(strict_types=1);

namespace App\Core;

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
	 * Adds a text, password, or email input field to the form with optional validation.
	 *
	 * @param string $name The name of the input field.
	 * @param string $label The label for the input field.
	 * @param string|null $type The type of the input ('text', 'password', 'email').
	 * @param string|null $placeholder The placeholder text for the input field.
	 * @param string|bool|null $required Defines whether the field is required (true, false, string error message, or null).
	 * @param string|null $rule The validation rule (optional).
	 * @param string|null $ruleMessage The error message for the validation rule (optional).
	 * @param string|int|null $ruleValue The value for the validation rule (optional).
	 */
	public function addTextInput(
		string $name,
		string $label,
		?string $type = 'text',
		?string $placeholder = null,
		string|bool|null $required = null,
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

		// Set the required attribute if provided (Nette will handle the logic based on the value)
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
