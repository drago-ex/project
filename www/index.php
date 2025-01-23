<?php

declare(strict_types=1);

use App\Bootstrap;
use Nette\DI\ServiceCreationException;

// Composer autoload
require __DIR__ . '/../vendor/autoload.php';


/**
 * Application runner class to manage Nette application lifecycle.
 */
class ApplicationRunner
{
	private Bootstrap $bootstrap;


	public function __construct()
	{
		// Initialize the Bootstrap class for app configuration
		$this->bootstrap = new Bootstrap();
	}


	/**
	 * Run the Nette application.
	 */
	public function run(): void
	{
		try {
			// Create the container and get the application service
			$container = $this->bootstrap->createContainer();
			$app = $container->getByType(Nette\Application\Application::class);

			// Run the application
			$app->run();

		} catch (ServiceCreationException $e) {
			// Handle case when the application service is not found
			echo 'Application service not found: ' . $e->getMessage();
			exit;

		} catch (Throwable $e) {
			// Handle any other general exceptions
			echo 'Error: ' . $e->getMessage();
			exit;
		}
	}
}

// Initialize and run the application
(new ApplicationRunner())->run();
