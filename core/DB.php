<?php

namespace Core;

class DB
{
	use Singleton;

	private $_connection;

	public function __construct(string $path)
	{
		$this->_connection = new \SQLite3("$path.db");

		self::$instance = $this;
	}

	public function getConnection(): \SQLite3
	{
		return $this->_connection;
	}

}