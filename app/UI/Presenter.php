<?php

declare(strict_types=1);

namespace App\UI;

use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Template;


/**
 * Base presenter class for handling common functionality in the application.
 * It includes localization functionality through the TranslatorAdapter trait.
 *
 * @property-read Template $template The template used by the presenter
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
	use TranslatorAdapter;
}
