<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

class Post extends Model {
	
	function __construct()
	{
		$post = new static;
		print_r($post->db_connect());
		Hello();
	}

}


function Hello()
{
	echo "Hello World";
}