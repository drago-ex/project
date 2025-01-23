<?php

declare(strict_types=1);

namespace App\UI\Backend;

use Nette;
use Nette\Application\Routers\RouteList;


/**
 * Router class for handling backend routes.
 */
final class Router
{
	// Using StaticClass trait to enforce static usage of the class.
	use Nette\StaticClass;

	/**
	 * Creates and returns a RouteList object with the configured routes.
	 *
	 * This method defines the backend routing structure with language support.
	 * The route uses a dynamic language parameter and maps to the 'Admin:default' presenter.
	 *
	 * @return RouteList Configured RouteList with backend routes.
	 */
	public static function create(): RouteList
	{
		$router = new RouteList;

		// Define the route with an optional language parameter ('cs' or 'en')
		$router->withModule('Backend')
			->addRoute('[<lang=cs cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
