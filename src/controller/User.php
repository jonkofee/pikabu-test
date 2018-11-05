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

		$token = $model->add($name, $password, $email, $dob, $gender, $phone, $ip);

		return [
			'access_token' => $token
		];
	}

	/**
	 * @private
	 */
	public function editAction()
	{
		$id 				= $this->getCurrentUser()['id'];
		$name 			= $this->name;
		$password 	= $this->password;
		$email 			= $this->email;
		$dob 				= (new \DateTime($this->dob))->format('Y-m-d');
		$gender 		= $this->gender | 0;
		$phone 			= $this->phone;

		$model = new \Model\User();

		return $model->edit($id, $name, $password, $email, $dob, $gender, $phone);
	}

	/**
	 * @private
	 */
	public function infoAction()
	{
		$currentUser = $this->getCurrentUser();

		return [
			'name' => $currentUser['name'],
			'dob' => $currentUser['dob'],
			'gender' => $currentUser['gender'],
			'phone' => $currentUser['phone']
		];
	}

	/**
	 * @private
	 */
	public function deleteAction()
	{
		$id = $this->getCurrentUser()['id'];

		$model = new \Model\User();

		return $model->delete($id);
	}

}