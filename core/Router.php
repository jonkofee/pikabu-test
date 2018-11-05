<?php

namespace Core;

class Router
{

	/**
	 * @var Request
	 */
	private $_request;

	/**
	 * @var ConfigIni
	 */
	private $_routes;

	/**
	 * @var string
	 */
	private $_controller;

	/**
	 * @var string
	 */
	private $_action;

	/**
	 * Router constructor.
	 * @param Request $request
	 * @throws \Exception
	 */
	public function __construct(Request $request)
	{
		$config = new ConfigIni('src/config/router.ini');

		$this->_request = $request;
		$this->_routes 	= $config;

		$this->_handle();
	}

	/**
	 * @return Router
	 * @throws \Exception
	 */
	private function _handle(): self
	{
		$route = $this->_findRoute();

		$this->_controller 	= $route['controller'];
		$this->_action 			= $route['action'];

		return $this;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getController(): string
	{
		if (!$this->_controller) {
			throw new Exception("Не установлен контроллер для маршрута '{$this->_request->getMethod()} {$this->_request->getPath()}'", 500);
		}

		return $this->_controller;
	}

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function getAction(): string
	{
		if (!$this->_action) {
			throw new Exception("Не установлен метод-обработчик для маршрута '{$this->_request->getMethod()} {$this->_request->getPath()}'", 500);
		}

		return $this->_action;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	private function _findRoute(): array
	{
		$uri 		= $this->_request->getPath();
		$method = $this->_request->getMethod();

		foreach ($this->_routes as $route) {
			if ($route['method'] != $method) {
				continue;
			}

			if ($route['path'] == $uri) {
				return $route;
			}
		}

		throw new Exception('Такой маршрут отсутствует', 404);
	}

}