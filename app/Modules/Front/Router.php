<?php

declare(strict_types=1);

namespace App\Modules\Front;

use Nette;
use Nette\Application\Routers\RouteList;


final class Router
{
	use Nette\StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Front')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Home:default');

		return $router;
	}
}
