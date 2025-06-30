<?php

namespace App\Core\Forms;

use Drago\Forms\Forms;
use Drago\Forms\Inputs;


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
}
