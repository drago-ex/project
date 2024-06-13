<?php

declare(strict_types=1);

namespace App\UI;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


/**
 * @property BaseTemplate $template
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
}
