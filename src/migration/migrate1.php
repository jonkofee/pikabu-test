<?php

namespace Migration;

use Core\Migrate;

class migrate1 extends Migrate
{

	protected function up()
	{
		$this->_connection->query("
			CREATE TABLE users (
				id INTEGER PRIMARY KEY AUTOINCREMENT,
				name VARCHAR (255) NOT NULL UNIQUE,
				email VARCHAR (255) NOT NULL UNIQUE,
				password VARCHAR (255) NOT NULL,
				dob datetime NOT NULL,
				gender TINYINT(1),
				phone VARCHAR(11),
				datetime_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
				ip VARCHAR(15) NOT NULL,
				token VARCHAR(255)
			);"
		);
	}

}