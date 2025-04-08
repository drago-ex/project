<?php

declare(strict_types=1);

namespace App\UI;

use App\Core\User\User;
use Drago\Application\UI\Alert;
use Drago\Localization\TranslatorAdapter;
use Nette\Application\UI\Form;
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


	/**
	 * Adds a success callback to the form.
	 * Displays a flash message and executes an optional additional action.
	 *
	 * @param Form $form The form to which the callback is added
	 * @param string $message The success message to display
	 * @param callable|null $additionalAction Optional additional action (e.g., redirect)
	 * @return Form The form with added callback
	 */
	protected function addSuccessCallback(Form $form, string $message, ?callable $additionalAction = null): Form
	{
		$form->onSuccess[] = function () use ($message, $additionalAction) {
			$this->flashMessage($message, Alert::Info);
			if ($additionalAction) {
				$additionalAction();
			}
		};
		return $form;
	}
}
