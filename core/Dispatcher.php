<?php

namespace Core;

class Dispatcher
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
	 * @var Router
	 */
	private $_router;

	/**
	 * Dispatcher constructor.
	 * @throws \Exception
	 */
	public function __construct()
	{
		$this->_request = new \Core\Request();

		$this->_router = new \Core\Router($this->_request);

		$this->_response = new Response();
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function handle()
	{
		$controller = $this->_getController();
		$actionName = $this->_getAction();

		$response = $controller->$actionName();

		return $response;
	}

	/**
	 * @return Controller
	 * @throws \Exception
	 */
	private function _getController(): Controller
	{
		$controllerClassName = 'Controller\\' . $this->_router->getController();

		return new $controllerClassName($this->_request, $this->_response);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function _getAction(): string
	{
		return $this->_router->getAction();
	}

}