<?php

declare(strict_types=1);

namespace App\UI;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Presenter;


/**
 * Base presenter class for handling common functionality in the application.
 * It includes localization functionality through the TranslatorAdapter trait.
 *
 * @property-read BaseTemplate $template The template used by the presenter
 */
abstract class BasePresenter extends Presenter
{
	use TranslatorAdapter;
}
