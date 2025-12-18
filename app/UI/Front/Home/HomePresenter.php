<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\Core\Form\Factory;
use App\UI\Presenter;
use Drago\Application\UI\Alert;
use Drago\Form\Autocomplete;
use Nette\Application\UI\Form;
use Tracy\Debugger;


/**
 * HomePresenter handles the logic for the home page in the front-end module.
 *
 * @property-read HomeTemplate $template Template for rendering the home page.
 */
final class HomePresenter extends Presenter
{
	public function __construct(
		private readonly Factory $factory,
		private readonly FooControl $fooControl,
	) {
		parent::__construct();
	}


	protected function createComponentFooControl(): FooControl
	{
		return $this->fooControl;
	}


	protected function createComponentAdd(): Form
	{
		$form = $this->factory->create();
		$form->addTextInput(
			name: 'name',
			label: 'Name',
			required: true,
		)->setPlaceholder('Enter your name');

		$form->addTextInput(
			name: 'age',
			label: 'Age',
			type: 'number',
			required: true,
		)->setAutocomplete(Autocomplete::Off)
			->setPlaceholder('Enter your age');

		$form->addPasswordField();
		$form->addSubmit('send', 'Send');
		$form->onSuccess[] = $this->success(...);
		return $form;
	}


	public function success(Form $form): void
	{
		Debugger::barDump($form->getValues());
		$this->flashMessage('The form has been submitted.', Alert::Info);
		$this->redrawControl('factory');
		$this->redrawControl('message');
		$form->reset();
	}
}
