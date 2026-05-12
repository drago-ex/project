<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid\Exception;


/**
 * Thrown when DataGrid is not properly configured (e.g., actions without primary key).
 */
class InvalidConfigurationException extends DataGridException
{
}
