<?php

include_once('init.php');
include_once('vendor/autoload.php');

use System\Exceptions\Exc404;
use System\Router;
use System\ModulesDispatcher;
use Modules\CompletedDeals\Module as CompletedDeals;
use Modules\Booking\Module as Booking;

const BASE_URL = '/introvert_tasks/';
const DB_HOST = 'localhost';

try{	
	$modules = new ModulesDispatcher();
	$modules->add(new CompletedDeals());
	$modules->add(new Booking());

	$router = new Router(BASE_URL);
	
	$modules->registerRoutes($router);
	
	$uri = $_SERVER['REQUEST_URI'];
	$activeRoute = $router->resolvePath($uri);

	$c = $activeRoute['controller'];
	$m = $activeRoute['method'];

	$c->$m();
	$html = $c->render();
	echo $html;
}
catch(Exc404 $e){
	echo '404 here' . $e->getMessage() . $e->getFile() . $e->getLine();
}
catch(Throwable $e){
	echo 'nice show error - ' . $e->getMessage() . $e->getFile() . $e->getLine();
}