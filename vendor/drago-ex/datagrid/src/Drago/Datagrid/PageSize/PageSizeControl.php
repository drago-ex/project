<?php

/**
 * Drago Extension
 * Package built on the Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\PageSize;

use Closure;
use Drago\Datagrid\Options;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;


/**
 * @property-read PageSizeTemplate $template
 */
final class PageSizeControl extends Control
{
	private ?Closure $onPageChanged = null;

	/** Total number of items in the DataGrid */
	private int $totalItems = 0;

	/** Current number of items per page */
	private int $currentPageSize = Options::DefaultItemsPerPage;

	private ?Translator $translator = null;


	public function setTranslator(?Translator $translator): void
	{
		$this->translator = $translator;
	}


	/**
	 * Registers a callback executed when page size is changed.
	 */
	public function onPageChanged(callable $callback): void
	{
		$this->onPageChanged = $callback;
	}


	/**
	 * Sets total number of items in the DataGrid.
	 */
	public function setTotalItems(int $totalItems): void
	{
		$this->totalItems = $totalItems;
	}


	/**
	 * Sets current page size.
	 */
	public function setCurrentPageSize(int $size): void
	{
		$this->currentPageSize = $size;
	}


	/**
	 * Creates form for selecting number of items per page.
	 */
	protected function createComponentForm(): Form
	{
		$form = new Form;
		$form->setMethod(Form::GET);

		$form->addSelect('pageSize', 'Items per page', items: [
			20 => '20',
			50 => '50',
			100 => '100',
			0 => 'All',
		])
			->setDefaultValue($this->currentPageSize)
			->setHtmlAttribute('data-items-page');

		$form->onSuccess[] = function (Form $form, \stdClass $values): void {
			if ($this->onPageChanged) {
				($this->onPageChanged)(Options::DefaultPage, (int) $values->pageSize);
			}
		};

		return $form;
	}


	public function handleSetPageSize($size): void
	{
		if ($this->onPageChanged) {
			($this->onPageChanged)(Options::DefaultPage, (int) $size);
		}
	}


	/**
	 * Renders page size control.
	 */
	public function render(): void
	{
		$template = $this->template;
		if ($this->translator !== null) {
			$template->setTranslator($this->translator);
		}
		$template->setFile(__DIR__ . '/PageSize.latte');
		$this->template->items = [
			20 => '20',
			50 => '50',
			100 => '100',
			0 => 'All',
		];
		$this->template->currentSize = $this->currentPageSize;
		$this->template->render();
	}
}
