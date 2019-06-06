<?php

declare(strict_types = 1);

namespace App;

use Nette\SmartObject;


/**
 * Environment in application.
 */
class Environment
{
	use SmartObject;

	/** @var bool */
	private $environment;


	public function __construct(bool $environment)
	{
		$this->environment = $environment;
	}


	/**
	 * Returned environment in application.
	 */
	public function isProduction(): bool
	{
		$environment = $this->environment;
		return $environment;
	}
}
