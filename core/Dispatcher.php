<?php

namespace Core;

use Model\User;

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
	 * @var array | null
	 */
	private $_currentUser;

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

		$this->_checkAccess($controller, $actionName);

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

		return new $controllerClassName($this->_request, $this->_response, $this->_currentUser);
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	private function _getAction(): string
	{
		return $this->_router->getAction();
	}

	/**
	 * @param Controller $controller
	 * @param string $method
	 * @return bool
	 * @throws \ReflectionException | \Exception
	 */
	private function _checkAccess(Controller $controller, string $method)
	{
		$reflector = new \ReflectionClass($controller);

		$methodDoc = $reflector->getMethod($method . 'Action')->getDocComment();

		if (!$methodDoc || !preg_match('/@private/', $methodDoc)) {
			return true;
		}

		$token = $this->_request->access_token;

		if (!$token) {
			throw new \Exception("Доступ к закрытому методу без 'access_token' запрещен", 204);
		}

		$currentUser = (new User())->getUserByToken($token);

		if (!$currentUser) {
			throw new \Exception("Доступ запрещен", 204);
		}

		return true;
	}

}