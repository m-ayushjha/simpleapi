<?php

// require '../database/Collection.php';

// use Database\Connection as Connection;

class Model {

	public function db_connect() {
		return Connection::make();
	}

}
