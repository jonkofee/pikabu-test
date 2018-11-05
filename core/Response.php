<?php

namespace Core;

class Response
{

	/**
	 * @var array
	 */
	private $_headers;

	/**
	 * @var int
	 */
	private $_code;

	/**
	 * @var array
	 */
	private $_body = [];

	/**
	 * Response constructor.
	 * @param mixed $body
	 * @param int $code
	 */
	public function __construct($body = [], int $code = 200)
	{
		$this->_headers['Content-type'] = 'application/json';

		$this->_code = $code;

		$this->_body = $body;
	}

	/**
	 * @param int $code
	 * @return Response
	 */
	public function setCode(int $code): self
	{
		$this->_code = $code;

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return Response
	 */
	public function addHeader(string $key, string $value): self
	{
		$this->_headers[$key] = $value;

		return $this;
	}

	/**
	 * @param $body
	 * @return Response
	 */
	public function setBody($body): self
	{
		if ($body) {
			$this->_body = $body;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function __toString(): string
	{
		foreach ($this->_headers as $key => $value) {
			header("$key: $value");
		}

		http_response_code($this->_code);

		$isSuccess = $this->_code >= 200 && $this->_code < 300;

		return json_encode([
			'result' => $isSuccess,
			'message' => !$isSuccess ? $this->_body : '',
			'data' => $isSuccess ? $this->_body : []
		]);
	}

}