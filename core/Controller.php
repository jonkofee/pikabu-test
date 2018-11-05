<?php
namespace Core;

abstract class Controller
{
	/**
	 * @var Request
	 */
	private $_request;

	/**
	 * @var Response
	 */
	private $_response;

	/**
	 * Controller constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct(Request $request, Response $response)
	{
		$this->_request = $request;

		$this->_response = $response;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return Response
	 * @throws \Exception
	 */
	public function __call($name, $arguments): Response
	{
		$actionName = $name . 'Action';

		if (!method_exists($this, $actionName)) {
			throw new \Exception("Метод '$name' не существует в контроллере '" . get_class($this) . "'", 500);
		}

		$result = $this->$actionName();

		$this->_response->setBody($result);

		return $this->_response;
	}

	/**
	 * @param $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->_request->$name;
	}

	/**
	 * @param int $code
	 * @return Controller
	 */
	protected function setCode(int $code): self
	{
		$this->_response->setCode($code);

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return Controller
	 */
	protected function addHeader(string $key, string $value): self
	{
		$this->_response->addHeader($key, $value);

		return $this;
	}

}