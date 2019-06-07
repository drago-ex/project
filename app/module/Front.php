<?php

declare(strict_types = 1);

namespace App;

use Drago\Localization\Locale;
use Drago\Localization\Translator;


/**
 * Base class for frontend module.
 */
class FrontPresenter extends BasePresenter
{
	use Locale;

	protected function beforeRender(): void
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
