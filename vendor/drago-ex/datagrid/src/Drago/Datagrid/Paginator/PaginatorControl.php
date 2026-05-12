<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Paginator;

use Closure;
use Drago\Datagrid\Options;
use Nette\Application\UI\Control;
use Nette\Localization\Translator;
use Nette\Utils\Paginator as UtilsPaginator;


/**
 * Paginator UI control for DataGrid.
 * Handles page changes, sorting, and rendering of pagination component.
 *
 * @property-read PaginatorTemplate $template
 */
final class PaginatorControl extends Control
{
	private UtilsPaginator $paginator;

	private ?Closure $onPageChanged = null;

	/** Current sorting column */
	private ?string $column = null;

	/** Current sorting order */
	private string $order = Options::OrderAsc;

	private ?Translator $translator = null;


	public function __construct()
	{
		$this->paginator = new UtilsPaginator;
	}


	public function setTranslator(?Translator $translator): void
	{
		$this->translator = $translator;
	}


	/**
	 * Set paginator state.
	 *
	 * @param int $page Current page
	 * @param int $itemsPerPage Items per page
	 * @param int $itemCount Total items
	 */
	public function setPaginator(int $page, int $itemsPerPage, int $itemCount): void
	{
		$this->paginator->setPage($page);
		$this->paginator->setItemsPerPage($itemsPerPage);
		$this->paginator->setItemCount($itemCount);
	}


	/**
	 * Register a callback to be called when the page changes.
	 */
	public function onPageChanged(callable $callback): void
	{
		$this->onPageChanged = $callback;
	}


	/**
	 * Handle page change signal.
	 */
	public function handlePage(int $page, ?string $column = null, ?string $order = null): void
	{
		$this->paginator->setPage($page);

		if ($column !== null) {
			$this->column = $column;
		}
		if ($order !== null) {
			$this->order = $order;
		}

		if ($this->onPageChanged) {
			($this->onPageChanged)($page, $this->column, $this->order);
		}
		$this->redrawControl();
	}


	/**
	 * Set current sorting column and order.
	 */
	public function setSorting(?string $column, string $order): void
	{
		$this->column = $column;
		$this->order = $order;
	}


	/**
	 * Render paginator component.
	 */
	public function render(): void
	{
		$template = $this->template;
		if ($this->translator !== null) {
			$template->setTranslator($this->translator);
		}
		$template->setFile(__DIR__ . '/Paginator.latte');
		$template->paginator = $this->paginator;
		$template->order = $this->order;
		$template->column = $this->column;
		$template->render();
	}
}
