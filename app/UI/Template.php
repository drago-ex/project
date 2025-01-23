<?php

declare(strict_types=1);

namespace App\UI;

use Drago\Application\UI\ExtraTemplate;


/**
 * Custom template class that extends the ExtraTemplate class.
 * It includes an additional language property.
 */
class Template extends ExtraTemplate
{
	/** @var string The language used in the template */
	public string $lang;
}
