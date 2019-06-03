<?php

declare(strict_types = 1);

namespace Module\Web;

use Drago\Application\UI\Factory;
use Nette\Application\UI\Presenter;
use Base;

final class WebPresenter extends Presenter
{
	use Base;
	use Factory;
}
