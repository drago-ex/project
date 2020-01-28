<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Nette\Bridges\ApplicationLatte\Template;
use stdClass;

/**
 * @property-read Template|stdClass $template
 */
trait Base
{
	use Locale;

	/** @var string */
	public $translateFile;


	public function injectOnRender(): void
	{
		$this->onRender[] = function () {
			$this->template->lang = $this->lang;
			$this->template->setTranslator($this->getTranslator());
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
