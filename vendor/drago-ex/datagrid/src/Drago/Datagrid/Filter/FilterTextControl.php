<?php

declare(strict_types=1);

namespace Drago\Datagrid\Filter;

use Closure;
use Drago\Datagrid\Column\Column;
use Drago\Datagrid\Options;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Localization\Translator;
use stdClass;


/**
 * DataGrid filter component.
 *
 * @property-read FilterTextTemplate $template
 */
final class FilterTextControl extends Control
{
	private ?Closure $onFilterChanged = null;
	private ?Closure $onReset = null;
	private string $filterMode = Options::FilterModeTop;

	/** @var Column[] */
	private array $columns = [];

	/** @var array<string, mixed> */
	private array $values = [];

	private bool $hasActiveFilters = false;
	private ?Translator $translator = null;


	public function setTranslator(?Translator $translator): void
	{
		$this->translator = $translator;
	}


	public function onFilterChanged(callable $callback): void
	{
		$this->onFilterChanged = $callback;
	}


	public function setFilterMode(string $mode): void
	{
		$this->filterMode = $mode;
	}


	public function getFormId(): string
	{
		$path = $this->lookupPath('Nette\Application\UI\Presenter');
		return 'dg-filter-' . str_replace(['-', ':'], '_', $path);
	}


	public function onReset(callable $callback): void
	{
		$this->onReset = $callback;
	}


	public function handleResetFilters(): void
	{
		$this->values = [];
		$this->hasActiveFilters = false;

		if ($this->onReset) {
			($this->onReset)();
		}
	}


	/**
	 * @param Column[] $columns
	 */
	public function setColumns(array $columns): void
	{
		$this->columns = $columns;
	}


	public function setValues(array $values): void
	{
		$this->values = $values;
		$this->hasActiveFilters = false;

		foreach ($values as $value) {
			if ($value !== null && $value !== '') {
				$this->hasActiveFilters = true;
				break;
			}
		}
	}


	protected function createComponentForm(): Form
	{
		$form = new Form;
		$form->setMethod(Form::GET);
		$form->setTranslator($this->translator);

		foreach ($this->columns as $column) {
			if ($column->filter !== null) {

				$type = $column->filter->getInputType();
				$name = $column->name;

				if ($type === 'text') {
					$form->addText($name, $column->label)
						->setDefaultValue($this->values[$name] ?? '')
						->setHtmlAttribute('data-items-filter')
						->setHtmlAttribute('placeholder', 'Search...')
						->setHtmlAttribute('autocomplete', 'off');

				} elseif ($type === 'select') {
					$items = $column->filter instanceof SelectFilter ? $column->filter->items : [];
					$form->addSelect($name, $column->label, $items)
						->setPrompt('All')
						->setDefaultValue($this->values[$name] ?? '')
						->setHtmlAttribute('data-items-filter');

				} elseif ($type === 'date') {
					$form->addText($name, $column->label)
						->setHtmlType('date')
						->setDefaultValue($this->values[$name] ?? '')
						->setHtmlAttribute('data-items-filter');
				}
			}
		}

		$form->onSuccess[] = function (Form $form, stdClass $values): void {
			$valuesArray = (array) $values;

			$this->setValues($valuesArray);

			if ($this->onFilterChanged) {
				($this->onFilterChanged)($valuesArray);
			}
		};

		return $form;
	}


	public function render(): void
	{
		$template = $this->template;
		if ($this->translator !== null) {
			$template->setTranslator($this->translator);
		}

		if ($this->filterMode === Options::FilterModeInline) {
			$this['form']->getElementPrototype()->id = $this->getFormId();
		}

		$templateFile = $this->filterMode === Options::FilterModeInline
			? __DIR__ . '/FilterInline.latte'
			: __DIR__ . '/Filter.latte';
		$this->template->setFile($templateFile);
		$this->template->hasActiveFilters = $this->hasActiveFilters;
		$this->template->filterValues = $this->values;
		$this->template->render();
	}
}
