<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


$route['alert-level']['get'] = 'alertLevelController';
$route['alert-level/(:num)']['get'] = 'alertLevelController/getById/$1';

$route['hak-akses']['get'] = 'hakAksesController';
$route['hak-akses/(:num)']['get'] = 'hakAksesController/getById/$1';

$route['role']['get'] = 'roleController';
$route['role/(:num)']['get'] = 'roleController/getById/$1';

$route['unit']['get'] = 'unitController';
$route['unit/(:num)']['get'] = 'unitController/getById/$1';
$route['unit']['post'] = 'unitController/create';
$route['unit/(:num)']['options'] = 'unitController/options';
$route['unit/(:num)']['put'] = 'unitController/update/$1';
$route['unit/(:num)']['delete'] = 'unitController/delete/$1';

$route['unit-category']['get'] = 'unitCategoryController';
$route['unit-category/(:num)']['get'] = 'unitCategoryController/getById/$1';

$route['user']['get'] = 'userController';
$route['user/(:num)']['get'] = 'userController/getById/$1';
$route['user']['post'] = 'userController/create';
$route['user/(:num)']['options'] = 'userController/options';
$route['user/(:num)']['put'] = 'userController/update/$1';
$route['user/(:num)']['delete'] = 'userController/delete/$1';

$route['user-role']['get'] = 'userRoleController';
$route['user-role/(:num)']['get'] = 'userRoleController/getById/$1';

$route['current/beban']['get'] = 'current/currentBebanController';
$route['authentication/verify']['post'] = 'authenticationController/verify';
?>