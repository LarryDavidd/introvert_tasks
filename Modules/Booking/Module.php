<?php

namespace Modules\Booking;

use System\Contracts\IModule;
use System\Contracts\IRouter;
use Modules\Booking\Controllers\Index as C;

class Module implements IModule{
	public function registerRoutes(IRouter $router) : void {
		$router->addRoute('booking', contorllerName: C::class);
    $router->addRoute('booking/dates', C::class, 'dates');
	}
}

