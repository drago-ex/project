<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use App\Core\Factory;
use App\UI\Presenter;
use Drago\Application\UI\Alert;
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
		$form->addText('name', 'Name')
			->setRequired('Name is required.');

		$form->addInteger('age', 'Age')
			->setRequired('Age is required.');

		$form->addPassword('password', 'Password')
			->setRequired('Password is required.');

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
