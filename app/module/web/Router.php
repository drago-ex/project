<?php

namespace Module\Web;

use Nette;
use Nette\Application\Routers;

final class Router
{
	use Nette\StaticClass;


	public static function create(): Routers\RouteList
	{
		$router = new Routers\RouteList;
		$router
			->withModule('Web')
			->addRoute('<presenter>/<action>', 'Web:default');

		return $router;
	}
}
