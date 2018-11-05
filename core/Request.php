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
	private $_data;

	/**
	 * Request constructor.
	 */
	public function __construct()
	{
		$this->_method 	= $_SERVER['REQUEST_METHOD'];
		$this->_path 		= $_SERVER['REQUEST_URI'];
		$this->_data 		= array_merge($_GET, $_POST);
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
		return $this->_data[$name];
	}

}