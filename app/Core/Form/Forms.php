<?php

declare(strict_types=1);

namespace App\Core\Form;

use Nette\Application\UI\Form;


/**
 * Extended Nette's Form class with helper methods to quickly add and configure
 * text, password, and email input fields. It simplifies form creation with
 * optional validation, placeholders, and required messages.
 */
class Forms extends Form
{
	public const string

		// User data for login and registration
		Username = 'username',
		CurrentPassword = 'current-password',
		NewPassword = 'new-password',
		Email = 'email',

		// Personal information (name)
		Name = 'name',
		GivenName = 'given-name',
		FamilyName = 'family-name',

		// Contact information (phone number)
		Tel = 'tel',

		// Address (for shipping, billing, etc.)
		AddressLine1 = 'address-line1',
		AddressLine2 = 'address-line2',
		Country = 'country',
		PostalCode = 'postal-code',
		City = 'city',

		// Payment details (credit card)
		CcName = 'cc-name',
		CcNumber = 'cc-number',
		CcExp = 'cc-exp',
		CcCsc = 'cc-csc',

		// Specific information for shipping and billing
		Shipping = 'shipping',
		Billing = 'billing',
		Transaction = 'transaction';

	// List of allowed types
	private const array AllowedTypes = [
		'text',
		'password',
		'email',
		'number',
		'tel',
	];


	/**
	 * Adds a text input field to the form with optional validation.
	 *
	 * @param string $name The name of the input field.
	 * @param string $label The label for the input field.
	 * @param string|null $type The type of the input ('text', 'password', 'email').
	 * @param string|null $placeholder The placeholder text for the input field.
	 * @param string|bool|null $required Defines whether the field is required.
	 * @param string|null $rule The validation rule.
	 * @param string|null $ruleMessage The error message for the validation rule.
	 * @param string|int|null $ruleValue The value for the validation rule.
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
	): Inputs
	{
		// Checking the validity of a type if one is specified
		if ($type !== null && !in_array($type, self::AllowedTypes, true)) {
			throw new \InvalidArgumentException("Invalid type '$type' provided for input '$name'.");
		}

		$input = new Inputs($label);
		$input->setHtmlType($type ?? 'text');

		// Set optional attributes if provided
		if ($placeholder !== null) {
			$input->setPlaceholder($placeholder);
		}

		// Set the required attribute if provided
		if ($required !== null) {
			$input->setRequired($required);
		}

		// Apply validation rule if provided
		if ($rule) {
			$input->addRule($rule, $ruleMessage, $ruleValue);
		}

		$this->addComponent($input, $name);
		return $input;
	}


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
}
