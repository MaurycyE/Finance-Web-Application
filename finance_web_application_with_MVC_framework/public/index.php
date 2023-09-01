<?php

ini_set('session.cookie_lifetime', 864000); // ten days in seconds

require dirname(__DIR__) . '/vendor/autoload.php';


error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

session_start();

$router = new Core\Router();

$router->add('api/limit/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ]+}', ['controller'=>'Addexpense', 'action'=>'limit']);
//$router->add('api/sum/{date:[^\d{4}-\d{2}-\d{2}$]}/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ]+}', ['controller'=>'Addexpense', 'action'=>'sum']);
//$router->add('api/sum/{date:[\d\d\d\d\-\d\d\-\d\d]+}', ['controller'=>'Addexpense', 'action'=>'sum']);
$router->add('api/sum/{date:((?:19|20)\\d\\d)-(0?[1-9]|1[012])-([12][0-9]|3[01]|0?[1-9])}/{category:[\wżźćńółęąśŻŹĆĄŚĘŁÓŃ]+}', ['controller'=>'Addexpense', 'action'=>'sum']);

$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
$router->add('{controller}/{action}');
$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);

$router->dispatch($_SERVER['QUERY_STRING']);
