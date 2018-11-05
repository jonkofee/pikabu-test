<?php

namespace Core;


abstract class Migrate
{
	/**
	 * @var \SQLite3
	 */
	protected $_connection;

	/**
	 * Migrate constructor.
	 */
	public function __construct()
	{
		$this->_connection = DB::getInstance()->getConnection();
	}

	/**
	 * @throws Exception
	 */
	public function run(): void
	{
		try {
			$this->up();
			$this->updateLastMigrate();
		} catch (\Exception $e) {
				throw new Exception($this->_connection->lastErrorMsg());
		}
	}

	private function updateLastMigrate(): void
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