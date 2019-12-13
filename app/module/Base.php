<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Nette\Application\UI\Presenter;
use stdClass;


/**
 * @property-read  Presenter|stdClass  $presenter
 */
trait Base
{
	use Locale;

	/** @var string */
	private $translateFile;


	public function injectOnRender()
	{
		$this->presenter->onRender[] = function () {
			$template = $this->presenter->template;
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
