<?php

namespace Controller;

use Core\Controller;

class Auth extends Controller
{

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

	public function indexAction()
	{
		$email 		= $this->email;
		$password = $this->password;

		$model = new \Model\User();

		$user = $model->getUserByEmailAndPassword($email, $password);

		if (!$user) {
			throw new \Exception("Не верная почта или пароль", 400);
		}

		return $model->getNewToken($user['id']);
	}

}