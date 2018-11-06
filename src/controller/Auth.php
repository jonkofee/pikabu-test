<?php

namespace Controller;

use Core\Controller;

class Auth extends Controller
{

	/**
	 * @var array
	 */
	protected $validateRules = [
		'email' => [
			'filter' => FILTER_VALIDATE_EMAIL,
			'message' => 'Не верный формат почты'
		],
		'password' => [
			'regexp' => '/^.{6,255}$/',
			'message' => 'Пароль должен состоять от 6 до 255 символов'
		]
	];

	/**
	 * @return string
	 * @throws \Exception
	 */
	public function indexAction(): string
	{
		$email 		= $this->email;
		$password = $this->password;

		$model = new \Model\User();

		$user = $model->getUserByEmail($email);

		if (!$user || !password_verify($password, $user['password'])) {
			throw new \Exception("Не верная почта или пароль", 400);
		}

		return $model->getNewToken($user['id']);
	}

}