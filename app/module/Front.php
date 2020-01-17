<?php

declare(strict_types = 1);

namespace App;

use Drago\Parameters\Parameters;
use Nette\Application\UI\Presenter;


trait Front
{
	public function injectOnStartup(Parameters $parameters, Presenter $presenter): void
	{
		$presenter->onStartup[] = function () use ($parameters) {
			$locale = $parameters->getAppDir();
			$this->translateFile = $locale . '/locale/';
		};
	}
}
