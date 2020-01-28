<?php

declare(strict_types = 1);

namespace App;

use Drago\Parameters\Parameters;


trait Front
{
	public function injectOnStartup(Parameters $parameters): void
	{
		$this->onStartup[] = function () use ($parameters) {
			$locale = $parameters->getAppDir();
			$this->translateFile = $locale . '/locale/';
		};
	}
}
