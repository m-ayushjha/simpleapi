<?php
require "core/bootstrap.php";

$uri = trim($_SERVER['REQUEST_URI'], '/');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$uri = trim(substr($uri, strlen('sampleapi')), '/');
}

$route = Router::load('routes.php')->direct($uri);

$Controller =  ucfirst($route['controller']);
$method = !empty($route['method'])? $route['method'] : ''; 
$id = !empty($route['id'])? $route['id'] : ''; 
$controller = new $Controller();

if(empty($id)) {
	$controller->$method();
}else {
	$controller->$method($id);
}