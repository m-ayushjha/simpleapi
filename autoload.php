<?php
require 'vendor/autoload.php'; // Called first
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

spl_autoload_register(function ($class){
	$controllers = array("Posts", "Profile", "Pages", "Dashboard");
	if(in_array($class, $controllers)){
		require "controllers/$class.php";
	} else {
		require "Api/$class.php";
	}
});