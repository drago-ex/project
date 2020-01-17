<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Nette\Application\UI\Presenter;


trait Base
{
	use Locale;

	/** @var string */
	public $translateFile;


	public function injectOnRender(Presenter $presenter): void
	{
		$presenter->onRender[] = function () use ($presenter) {
			$template = $presenter->template;
			$template->lang = $this->lang;
			$template->setTranslator($this->getTranslator());
		};
	}


	/**
	 * @throws \Exception
	 */
	public function getTranslator(): Translator
	{
		$file = $this->translateFile . $this->lang;
		return $this->createTranslator($file);
	}
}
