<?php

declare(strict_types = 1);

/**
 * Drago Project
 * Package built on Nette Framework
 */
namespace App;

use Drago\Configurator;

/**
 * Configure the application.
 * @package App
 */
class Bootstrap
{
	/**
	 * Setup configuration.
	 */
	public static function boot(): Configurator
	{
		$app = new Configurator();

		// Enable Tracy tool.
		$app->enableTracy(__DIR__ . '/../log');

		// Set the time zone.
		$app->setTimeZone('Europe/Prague');

		// Directory of temporary files.
		$app->setTempDirectory(__DIR__ . '/../storage');

		// Auto-loading classes.
		$app->addAutoload(__DIR__);

		// Create DI container from configuration files.
		$app->addFindConfig(__DIR__);

		// Run application.
		$app->run();
	}
}
