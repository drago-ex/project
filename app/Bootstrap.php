<?php

declare(strict_types=1);

namespace App;

use Drago\Bootstrap\ExtraConfigurator;
use Nette\DI\Container;
use Throwable;


final class Bootstrap
{
	private ExtraConfigurator $configurator;
	private string $rootDir;


	public function __construct()
	{
		$this->rootDir = dirname(__DIR__);
		$this->configurator = new ExtraConfigurator;
		$this->configurator->setTempDirectory($this->rootDir . '/temp');
	}


	public function initializeEnvironment(): void
	{
		if (getenv('NETTE_DEBUG') === '1') {
			$this->configurator->setDebugMode(true);
		}

		$this->configurator->enableTracy($this->rootDir . '/log');
		$this->configurator->createRobotLoader()
			->addDirectory(__DIR__)
			->register();
	}


	/** @throws Throwable */
	public function bootWebApplication(): Container
	{
		$this->initializeEnvironment();
		$this->setupContainer();
		return $this->configurator->createContainer();
	}


	/** @throws Throwable */
	public function bootConsoleApplication(): Container
	{
		$this->initializeEnvironment();
		$this->configurator->setDebugMode(false);
		$this->setupContainer();
		return $this->configurator->createContainer();
	}


	/** @throws Throwable */
	private function setupContainer(): void
	{
		$this->configurator->addFindConfig(__DIR__, 'Translate');
	}
}
