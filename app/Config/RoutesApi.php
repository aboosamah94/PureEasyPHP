<?php

namespace Config;

use Pureeasyphp\Api;

$routes = new Api();


/*
 * --------------------------------------------------------------------
 * Routes List
 * --------------------------------------------------------------------
 */

$routes->get('example', 'Example');
$routes->post('example', 'Example');
$routes->put('example', 'Example');
$routes->delete('example', 'Example');
$routes->patch('example', 'Example');

$routes->get('users', 'Users');


/*
 * --------------------------------------------------------------------
 * Router Return
 * --------------------------------------------------------------------
 */
return $routes;