<?php

declare(strict_types=1);

namespace App\Modules;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
}
