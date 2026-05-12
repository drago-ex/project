<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Column;

use Drago\Datagrid\Filter\SelectFilter;
use Drago\Datagrid\Filter\TextFilter;


/**
 * DataGrid column for displaying text values.
 * Supports optional cell formatting callback.
 */
class ColumnText extends Column
{
	public function setFilterText(): self
	{
		$this->setFilter(new TextFilter);
		return $this;
	}


	public function setFilterSelect(array $items): self
	{
		$this->setFilter(new SelectFilter($items));
		return $this;
	}


	/**
	 * Enable or disable natural numeric sorting for this column.
	 */
	public function setNaturalSort(bool $enable = true): static
	{
		parent::setNaturalSort($enable);
		return $this;
	}


	/**
	 * Renders the cell for this column.
	 * Applies optional formatter and escapes HTML characters.
	 * If the formatter returns a Nette\Utils\Html object, it is rendered directly.
	 */
	public function renderCell(array $row): string
	{
		$value = $row[$this->name] ?? '';

		if ($this->formatter) {
			$output = ($this->formatter)($value, $row);
		} else {
			$output = $value;
		}

		if ($output instanceof \Nette\Utils\Html) {
			return (string) $output;
		}

		return htmlspecialchars((string) $output, ENT_QUOTES, 'UTF-8');
	}
}
