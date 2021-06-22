<?php

declare(strict_types=1);

namespace App\Module;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
}
