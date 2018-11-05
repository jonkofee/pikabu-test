<?php

namespace Core;

class Request
{

	/**
	 * @var string
	 */
	private $_method;

	/**
	 * @var string
	 */
	private $_path;

	/**
	 * @var array
	 */
	private $_data = [];

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->_method 	= $_SERVER['REQUEST_METHOD'];
		$this->_path 		= $_SERVER['REQUEST_URI'];

		$this->_buildData();
	}

	/**
	 * @return string
	 */
	public function getMethod(): string
	{
		return $this->_method;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->_path;
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->_data;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get(string $name)
	{
		return $this->_data[$name] ?? null;
	}

	private function _buildData()
	{
		$data = $_REQUEST;

		foreach ($data as $key => &$value) {
			$value = trim($value);
			$value = \SQLite3::escapeString($value);

			if (preg_match('/^(\d+)$/', $value)) {
				$value = (int) $value;
			}
		}

		$this->_data = $data;
	}

}