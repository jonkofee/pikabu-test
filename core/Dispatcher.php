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
	 * @return Response
	 * @throws \Exception
	 */
	public function handle(): Response
	{
		$this->_checkAccess();

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
	 * @return bool
	 * @throws \ReflectionException | \Exception
	 */
	private function _checkAccess(): bool
	{
		$reflector = new \ReflectionClass('Controller\\' . $this->_router->getController());

		$methodDoc = $reflector->getMethod($this->_getAction() . 'Action')->getDocComment();

		if (!$methodDoc || !preg_match('/@private/', $methodDoc)) {
			return true;
		}

		$token = $this->_request->access_token;

		if (!$token) {
			throw new Exception("Доступ к закрытому методу без 'access_token' запрещен", 204);
		}

		$currentUser = (new User())->getUserByToken($token);

		if (!$currentUser) {
			throw new Exception("Доступ запрещен", 204);
		}

		$this->_currentUser = $currentUser;

		return true;
	}

}