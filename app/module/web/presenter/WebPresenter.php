<?php

declare(strict_types = 1);

namespace Module\Web;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


final class WebPresenter extends Presenter
{
	use TranslatorAdapter;
}
