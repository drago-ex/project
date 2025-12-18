<?php

declare(strict_types=1);

namespace App\Core\Form;

use Drago\Form\Autocomplete;
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
		)->setAutocomplete(Autocomplete::Off);
	}
}
