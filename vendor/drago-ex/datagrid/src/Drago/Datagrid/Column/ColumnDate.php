<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Column;

use Closure;
use Drago\Datagrid\Filter\DateFilter;
use Drago\Datagrid\Options;


/**
 * Date column with configurable format.
 */
class ColumnDate extends Column
{
	/**
	 * @param string $name Column identifier
	 * @param string $label Column label
	 * @param bool $sortable Enables sorting
	 * @param string $format Date format
	 * @param Closure|null $formatter Optional cell formatter
	 */
	public function __construct(
		string $name,
		string $label,
		bool $sortable = true,
		public readonly string $format = Options::DateFormat,
		?Closure $formatter = null,
	) {
		parent::__construct($name, $label, $sortable, $formatter);
	}


	/**
	 * Sets date filter for this column.
	 */
	public function setFilterDate(): self
	{
		$this->setFilter(new DateFilter);
		return $this;
	}


	/**
	 * Renders formatted date value.
	 * Formatter output is automatically escaped to prevent XSS.
	 */
	public function renderCell(array $row): string
	{
		$value = $row[$this->name] ?? null;
		if ($value === null) {
			return '';
		}

		$timestamp = is_numeric($value) ? (int) $value : strtotime((string) $value);
		if (!$timestamp) {
			return '';
		}

		$formatted = date($this->format, $timestamp);

		if ($this->formatter !== null) {
			$formatted = (string) ($this->formatter)($formatted, $row);
		}

		return htmlspecialchars($formatted, ENT_QUOTES, 'UTF-8');
	}
}
