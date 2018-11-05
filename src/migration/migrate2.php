<?php

namespace Migration;

use Core\Migrate;

class migrate2 extends Migrate
{

	protected function up()
	{
		$this->_connection->query("
		CREATE INDEX gender_users 
		ON users (gender);"
		);
	}

}