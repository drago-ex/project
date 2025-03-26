<?php

declare(strict_types=1);

namespace App\UI\Front\Home;

use Drago\Application\UI\ExtraControl;
use Drago\Components\Components;
use Drago\Components\ModalHandle;
use Drago\Components\OffcanvasHandle;
use Nette\Application\Attributes\Requires;
use Nette\Utils\Random;


/**
 * @property-read FooControlTemplate $template
 */
class FooControl extends ExtraControl implements ModalHandle, OffcanvasHandle
{
	use Components;

	public function render(): void
	{
		$template = $this->template;
		$template->setFile(__DIR__ . '/FooControl.latte');
		$template->setTranslator($this->translator);
		$template->offcanvasId = $this->getUniqueIdComponent(self::Offcanvas);
		$template->modalId = $this->getUniqueIdComponent(self::Modal);
		$template->random = Random::generate(5, 'A-Z');
		$template->render();
	}


	#[Requires(ajax: true)] public function handleOpenModal(): void
	{
		$this->modalComponent(self::Modal);
	}


	#[Requires(ajax: true)] public function handleOpenOffcanvas(): void
	{
		$this->offCanvasComponent(self::Offcanvas);
		$this->redrawControl();
	}
}
