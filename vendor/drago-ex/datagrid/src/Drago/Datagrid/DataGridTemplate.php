<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid;

use Drago\Datagrid\Column\Column;
use Nette\Bridges\ApplicationLatte\Template;


/**
 * Template class for DataGrid.
 * Holds rows, columns, sorting, actions, and pagination info for rendering in Latte.
 */
class DataGridTemplate extends Template
{
	/** Rows for the current page */
	public array $rows = [];

	/** @var Column[] Columns definitions */
	public array $columns = [];

	/** Currently sorted column name */
	public ?string $columnName = null;

	/** Sorting direction ('ASC' or 'DESC') */
	public string $order = 'ASC';

	/** @var Action[] Row actions */
	public array $actions = [];

	/** Primary key column name (required for actions) */
	public ?string $primaryKey = null;

	/** Current page number */
	public int $page = Options::DefaultPage;

	/** Number of items per page */
	public int $itemsPerPage = Options::DefaultItemsPerPage;

	/** Total number of items in the data source */
	public int $totalItems = 0;

	/** Current filter values */
	public array $filters = [];

	/** Whether any column has a filter configured */
	public bool $hasFilters = false;
	public string $filterMode = Options::FilterModeTop;
	public string $filterFormId = '';
}
