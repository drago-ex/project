<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Exception;


/**
 * Thrown when trying to add a duplicate column or access non-existent column.
 */
class InvalidColumnException extends DataGridException
{
}
