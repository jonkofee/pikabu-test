<?php

namespace Controller;

use Core\Controller;

class User extends Controller
{

	public function addAction()
	{
		$name 			= $this->name;
		$password 	= $this->password;
		$email 			= $this->email;
		$dob 				= (new \DateTime($this->dob))->format('Y-m-d');
		$gender 		= $this->gender | 0;
		$phone 			= $this->phone;
		$ip					= $_SERVER['REMOTE_ADDR'];

		$model = new \Model\User();

		return $model->add($name, $password, $email, $dob, $gender, $phone, $ip);
	}

}