<?php

declare(strict_types = 1);

namespace App;

use Drago\Parameters\Parameters;
use Nette\Application\UI\Presenter;
use stdClass;


/**
 * @property-read  Presenter|stdClass  $presenter
 */
trait Front
{
	/**
	 * @var Parameters
	 * @inject
	 */
	public $parameters;


	public function injectOnStartup(): void
	{
		$this->presenter->onStartup[] = function () {
			$locale = $this->parameters->getAppDir();
			$this->translateFile = $locale . '/locale/';
		};
	}
}
