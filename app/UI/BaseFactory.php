<?php

declare(strict_types=1);

namespace App\UI;

use Nette\Application\UI\Form;
use Nette\Localization\Translator;


class BaseFactory
{
	public function __construct(
		private readonly Translator $translator,
	) {
	}


	public function create(): Form
	{
		$form = new Form();
		$form->setTranslator($this->translator);
		return $form;
	}
}
