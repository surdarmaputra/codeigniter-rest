<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$route['users']['get'] = 'userController';
$route['users']['post'] = 'userController/create';
$route['users/(:num)']['get'] = 'userController/getById/$1';
$route['users/(:num)']['put'] = 'userController/update/$1';
$route['users/(:num)']['delete'] = 'userController/delete/$1';

?>