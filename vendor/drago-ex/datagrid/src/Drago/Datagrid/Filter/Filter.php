<?php

declare(strict_types=1);

namespace Drago\Datagrid\Filter;

use Dibi\Fluent;


interface Filter
{
	public function apply(Fluent $fluent, string $column, mixed $value): void;

	public function getInputType(): string;
}
