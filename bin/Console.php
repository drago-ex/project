<?php

declare(strict_types=1);

namespace bin;

use Exception;
use Nette\Bootstrap\Configurator;
use Symfony\Component\Console\Application;


class Console
{
	public static function boot(): Configurator
	{
		$app = new Configurator();
		$app->setDebugMode(true);
		$app->setTempDirectory(__DIR__ . '/');
		$app->addConfig(__DIR__ . '/generator.neon');
		$app->addConfig(__DIR__ . '/../app/db.neon');
		return $app;
	}


	/**
	 * @throws Exception
	 */
	public static function console(): int
	{
		return self::boot()
			->createContainer()
			->getByType(Application::class)
			->run();
	}
}
