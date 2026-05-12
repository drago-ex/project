<?php

/**
 * Drago Extension
 * Package built on the Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\PageSize;

use Nette\Bridges\ApplicationLatte\Template;


/**
 * Latte template for PageSizeControl
 */
class PageSizeTemplate extends Template
{
	public array $items;
	public int $currentSize;
}
