<?php

declare(strict_types = 1);

namespace Module\Web;

use Base;
use Nette\Application\UI\Presenter;

/**
 * Class WebPresenter
 * @package Module\Web
 */
final class WebPresenter extends Presenter
{
	use Base;

	protected function startup(): void
	{
		parent::startup();
		$this->environment->isProduction()
			? $this->setLayout('layout')
			: $this->setLayout('dev');
	}
}
