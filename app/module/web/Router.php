<?php

namespace Module\Web;

use Nette\Application\Routers\RouteList;
use Nette\Routing\Router;
use Nette\StaticClass;


final class RouterFactory
{
	use StaticClass;


	public static function create(): Router
	{
		$router = new RouteList;
		$router
			->withModule('Web')
			->addRoute('[<lang=cs cs|en>/]<presenter>/<action>', 'Web:default');

		return $router;
	}
}
