<?php

namespace Core;

abstract class Model
{

	/**
	 * @var \SQLite3
	 */
	protected $connection;

	/**
	 * Model constructor.
	 */
	public function __construct()
	{
		$this->connection = DB::getInstance()->getConnection();
	}

	/**
	 * @param string $query
	 * @param array $bindValues
	 * @return \SQLite3Result
	 * @throws Exception
	 */
	protected function query(string $query, array $bindValues = []): \SQLite3Result
	{
		$query = $this->connection->prepare($query);

		foreach ($bindValues as $bindValue) {
			$query->bindValue(...$bindValue);
		}

		$result = $query->execute();

		if ($result === false){
			throw new Exception($this->connection->lastErrorMsg(), 500);
		}

		return $result;
	}

}