<?php

declare(strict_types = 1);

namespace Module\Web;

use App\Base;
use Drago\Parameters\Parameters;
use Nette\Application\UI\Presenter;


final class WebPresenter extends Presenter
{
	use Base;

	/**
	 * @var Parameters
	 * @inject
	 */
	public $parameters;


	protected function startup(): void
	{
		parent::startup();
		$locale = $this->parameters->getAppDir();
		$this->translateFile = $locale . '/locale/';
	}
}
