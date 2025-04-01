<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\User\User;
use Drago\Localization\TranslatorAdapter;
use Nette\DI\Attributes\Inject;


/**
 * Base presenter class for handling common functionality in the application.
 * It includes localization functionality through the TranslatorAdapter trait.
 *
 * @property-read Template $template The template used by the presenter
 */
abstract class Presenter extends \Nette\Application\UI\Presenter
{
	use TranslatorAdapter;

	#[Inject]
	public User $user;


	/**
	 * Runs before rendering the page.
	 */
	protected function beforeRender(): void
	{
		parent::beforeRender();

		// Ensure the user is set and accessible in the template
		$this->template->user = $this->user;
	}
}
