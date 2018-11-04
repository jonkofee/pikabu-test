<?php

namespace Core;


abstract class Migrate
{
	protected $_connection;

	public function __construct()
	{
		$this->_connection = DB::getInstance()->getConnection();
	}

	public function run()
	{
		try {
			$this->up();
			$this->updateLastMigrate();
		} catch (\Exception $e) {
				throw new \Exception($this->_connection->lastErrorMsg());
		}
	}

	private function updateLastMigrate()
	{
		$config = ConfigIni::getInstance()['migrate'];

		$className = get_class($this);

		$migrateNumber = (int) preg_replace('/\w+\\\\\w+(\d+)/', '${1}', $className);

		$this->_connection->query("
			INSERT INTO {$config['table']} (number)
			VALUES ($migrateNumber);
		");
	}

}