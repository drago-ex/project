<?php

declare(strict_types = 1);

namespace Module\Web;

use Drago;
use Nette;


final class WebPresenter extends Nette\Application\UI\Presenter
{
	use Drago\Localization\TranslatorAdapter;
}
