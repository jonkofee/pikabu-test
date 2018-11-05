<?php

namespace Core;

class DB
{
	use Singleton;

	/**
	 * @var \SQLite3
	 */
	private $_connection;

	/**
	 * DB constructor.
	 * @param string $path
	 */
	public function __construct(string $path)
	{
		$this->_connection = new \SQLite3("$path.db");

		self::$instance = $this;
	}

	/**
	 * @return \SQLite3
	 */
	public function getConnection(): \SQLite3
	{
		return $this->_connection;
	}

}