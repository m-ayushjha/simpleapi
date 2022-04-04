<?php

class Connection
{

	public static function make() {
		$conn = new mysqli(
			env('DB_HOSTNAME'), 
			env('DB_USERNAME'), 
			env('DB_PASSWORD'), 
			env('DB_DATABASE')
		);	
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed");
  		}else {
			return $conn;
		}
	}

}


?>