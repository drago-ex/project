<?php

/**
 * Drago Extension
 * Package built on Nette Framework
 */

declare(strict_types=1);

namespace Drago\Datagrid;


/**
 * Represents an action in the DataGrid.
 * Can have a label, signal, optional CSS class, and callbacks to execute.
 */
class Action
{
	/** @var callable[] List of callbacks executed when the action is triggered */
	private array $callbacks = [];


	/**
	 * @param string $label Action label displayed in the UI
	 * @param string $signal Signal used for triggering this action
	 * @param string|null $class Optional CSS class for the action link/button
	 */
	public function __construct(
		public readonly string $label,
		public readonly string $signal,
		public readonly ?string $class = null,
	) {
	}


	/**
	 * Adds a callback to be executed when the action is triggered.
	 * @param callable $callback Callback function receiving the row ID
	 * @return $this Fluent interface
	 */
	public function addCallback(callable $callback): self
	{
		$this->callbacks[] = $callback;
		return $this;
	}


	/**
	 * Executes all registered callbacks with the given row ID.
	 * @param int $id ID of the row this action is executed for
	 */
	public function execute(int $id): void
	{
		foreach ($this->callbacks as $callback) {
			$callback($id);
		}
	}
}
