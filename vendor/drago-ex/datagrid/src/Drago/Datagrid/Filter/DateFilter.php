<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Filter;

use Dibi\Fluent;


/**
 * Date-based DataGrid filter.
 * Supports filtering by date range or specific date.
 */
class DateFilter implements Filter
{
	/**
	 * Applies date filter condition.
	 * Supports format: YYYY-MM-DD or range: YYYY-MM-DD|YYYY-MM-DD
	 */
	public function apply(Fluent $fluent, string $column, mixed $value): void
	{
		if ($value === null || $value === '') {
			return;
		}

		$value = (string) $value;

		// Check if it's a date range (format: YYYY-MM-DD|YYYY-MM-DD)
		if (str_contains($value, '|')) {
			[$fromDate, $toDate] = explode('|', $value, 2);
			$fromDate = trim($fromDate);
			$toDate = trim($toDate);

			if ($fromDate !== '' && $toDate !== '') {
				$this->validateDateFormat($fromDate);
				$this->validateDateFormat($toDate);
				$fluent->where('%n BETWEEN %s AND %s', $column, $fromDate, $toDate);
			} elseif ($fromDate !== '') {
				$this->validateDateFormat($fromDate);
				$fluent->where('%n >= %s', $column, $fromDate);
			} elseif ($toDate !== '') {
				$this->validateDateFormat($toDate);
				$fluent->where('%n <= %s', $column, $toDate);
			}
		} else {
			// Single date
			$this->validateDateFormat($value);
			$fluent->where('DATE(%n) = %s', $column, $value);
		}
	}


	/**
	 * Validates that date string is in YYYY-MM-DD format.
	 * @throws \InvalidArgumentException
	 */
	private function validateDateFormat(string $date): void
	{
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
			throw new \InvalidArgumentException(
				'Date filter expects YYYY-MM-DD format, got: \'' . $date . '\'',
			);
		}
	}


	/**
	 * Returns input type identifier.
	 */
	public function getInputType(): string
	{
		return 'date';
	}
}
