<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Filter;

use Dibi\Fluent;


/**
 * Select box filter for DataGrid.
 */
class SelectFilter implements Filter
{
	/**
	 * @param array $items Items for the select box [value => label]
	 */
	public function __construct(
		public readonly array $items,
	) {
	}


	/**
	 * Applies equality condition to the data source.
	 */
	public function apply(Fluent $fluent, string $column, mixed $value): void
	{
		if ($value === null || $value === '' || !isset($this->items[$value])) {
			return;
		}

		$fluent->where('%n = %s', $column, $value);
	}


	/**
	 * Returns input type identifier.
	 */
	public function getInputType(): string
	{
		return 'select';
	}
}
