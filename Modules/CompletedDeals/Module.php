<?php

namespace Modules\CompletedDeals;

use System\Contracts\IModule;
use System\Contracts\IRouter;
use Modules\CompletedDeals\Controllers\Index as C;

class Module implements IModule{
	public function registerRoutes(IRouter $router) : void {
		$router->addRoute('', contorllerName: C::class);
	}
}

