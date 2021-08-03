<?php

declare(strict_types=1);

namespace App\Modules\Frontend;

use Nette;
use Nette\Application\Routers\RouteList;


final class Router
{
	use Nette\StaticClass;

	public static function create(): RouteList
	{
		$router = new RouteList;
		$router->withModule('Frontend')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Frontend:default');

		return $router;
	}
}
