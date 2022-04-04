<?php
// Order of these files should be same
require 'autoload.php';
require 'func.php'; 
require "database/Connection.php";
// Order of these files should be same

$debug = (boolean) env('APP_DEBUG');

if ($debug) {
	error_reporting(E_ALL);
	ini_set('display_errors',1);
}

Request::db_connect(); // create db connection
require 'Api/fn.api.php';
require 'fn.database.php';
require "Router.php";
require "Model.php";




