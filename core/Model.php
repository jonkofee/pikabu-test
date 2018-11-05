<?php

namespace Core;

abstract class Model
{

	protected $connection;

	public function __construct()
	{
		$this->connection = DB::getInstance()->getConnection();
	}

	protected function query(string $query, array $bindValues = [])
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