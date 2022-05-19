<?php

declare(strict_types=1);

namespace App\Modules\Backend;

use Nette;
use Nette\Application\Routers\RouteList;


final class Router
{
	use Nette\StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Backend')
			->addRoute('[<lang=cs cs|en>/]admin/<presenter>/<action>', 'Admin:default');

		return $router;
	}
}
