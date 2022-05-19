<?php

declare(strict_types=1);

namespace App\Modules;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;

/**
 * @property-read BaseTemplate $template
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;


	protected function beforeRender(): void
	{
		$this->template->module = $this->getName() . ':' . $this->getView();
	}
}
