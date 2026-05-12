<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Column;

use Closure;
use Drago\Datagrid\Filter\Filter;


/**
 * Base DataGrid column definition.
 */
abstract class Column
{
	/** Whether to use natural (numeric) sorting when values contain numbers */
	private bool $naturalSort = false;


	/**
	 * @param string $name Column identifier
	 * @param string $label Column label
	 * @param bool $sortable Enables sorting
	 * @param Closure|null $formatter Optional value formatter
	 * @param Filter|null $filter Optional column filter
	 */
	public function __construct(
		public readonly string $name,
		public readonly string $label,
		public readonly bool $sortable,
		public readonly ?Closure $formatter,
		public ?Filter $filter = null,
	) {
	}


	/**
	 * Assigns a filter to the column.
	 */
	public function setFilter(Filter $filter): static
	{
		$this->filter = $filter;
		return $this;
	}


	/**
	 * Enable or disable natural numeric sorting for this column.
	 */
	public function setNaturalSort(bool $enable = true): static
	{
		$this->naturalSort = $enable;
		return $this;
	}


	/**
	 * Returns whether natural sorting is enabled for this column.
	 */
	public function isNaturalSort(): bool
	{
		return $this->naturalSort;
	}


	/**
	 * Renders a cell value for a given row.
	 */
	abstract public function renderCell(array $row): string;
}
