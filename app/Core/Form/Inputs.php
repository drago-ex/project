<?php

declare(strict_types=1);

namespace App\Core\Form;

use Nette\Forms\Controls\TextInput;


/**
 * Custom text input class that extends Nette's TextInput,
 * adding a convenient method for setting the autocomplete attribute.
 */
class Inputs extends TextInput
{
	public function __construct($label, ?string $maxLength = null)
	{
		parent::__construct($label, $maxLength);
	}


	/**
	 * Set the autocomplete attribute for the input field.
	 *
	 * @param string $autocompleteValue The value for the autocomplete attribute.
	 */
	public function setAutocomplete(string $autocompleteValue): self
	{
		return $this->setHtmlAttribute('autocomplete', $autocompleteValue);
	}
}
