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
	public function injectOnStartup(Parameters $parameters): void
	{
		$this->presenter->onStartup[] = function () use ($parameters) {
			$locale = $parameters->getAppDir();
			$this->translateFile = $locale . '/locale/';
		};
	}
}
