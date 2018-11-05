<?php

namespace Controller;

use Core\Controller;

class Auth extends Controller
{

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