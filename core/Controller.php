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

	private $_currentUser;

	/**
	 * Controller constructor.
	 * @param Request $request
	 * @param Response $response
	 */
	public function __construct(Request $request, Response $response, $currentUser)
	{
		$this->_request = $request;

		$this->_response = $response;

		$this->_currentUser = $currentUser;
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
			throw new Exception("Метод '$name' не существует в контроллере '" . get_class($this) . "'", 500);
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

	protected function getCurrentUser()
	{
		return $this->_currentUser;
	}

	public function validate(array $fields)
	{
		$errors = [];

		foreach ($fields as $field) {
			if (!isset($this->validateRules[$field])) {
				throw new Exception("Не установлено правило валидации для поля $field", 500);
			}

			$validateRule = $this->validateRules[$field];

			if (isset($validateRule['regexp'])) {
				if (!preg_match($validateRule['regexp'], $this->{$field})) {
					$errors[] = $validateRule['message'];
				}
				continue;
			}

			if (isset($validateRule['filter'])) {
				if (!filter_var($this->{$field}, $validateRule['filter'])) {
					$errors[] = $validateRule['message'];
				}
			}
		}

		if ($errors) {
			throw new Exception(implode(', ', $errors), 400);
		}
	}

}