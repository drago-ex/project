<?php

declare(strict_types=1);

namespace App\UI\Error\Error4xx;

use Nette\Bridges\ApplicationLatte\Template;


/**
 * BaseTemplate class for handling 4xx errors.
 * This class extends the base BaseTemplate class and adds an HTTP code property.
 */
class Error4xxTemplate extends Template
{
	/** @var mixed The HTTP status code for the error */
	public mixed $httpCode;
}
