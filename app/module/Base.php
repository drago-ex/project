<?php

use Nette\Application\UI\Presenter;
use App\Environment;

/**
 * Trait Base
 * @property Presenter|stdClass $presenter
 */
trait Base
{
	/** @var Environment @inject */
	public $environment;
}
