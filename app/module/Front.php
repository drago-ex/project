<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Locale;
use Drago\Localization\Translator;
use Nette\Application\UI\Presenter;

/**
 * Class BasePresenter
 * @package App
 */
abstract class FrontPresenter extends Presenter
{
	use Locale;

	/** @var Environment @inject */
	public $environment;


	protected function startup(): void
	{
		parent::startup();
		$mode = $this->environment->isProduction();
		$mode ? $this->setLayout('layout') : $this->setLayout('dev');
	}


	protected function beforeRender()
	{
		parent::beforeRender();

		// The current language parameter.
		$this->template->lang = $this->lang;

		// Translation for Templates.
		$this->template->setTranslator($this->getTranslator());

	}


	public function getTranslator(): Translator
	{
		$file = __DIR__ . '/web/locale/' . $this->lang . '.ini';
		return $this->createTranslator($file);
	}
}
