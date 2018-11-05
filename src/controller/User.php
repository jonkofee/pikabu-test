<?php

namespace Controller;

use Core\Controller;

class User extends Controller
{

	/**
	 * @var array
	 */
	protected $validateRules = [
		'name' => [
			'regexp' => '/^[a-zA-zа-яА-Я.-]+$/',
			'message' => 'Имя пользователя может состоять из символов русского и английского алфавитов, цифр, а также символов точки и дефиса'
		],
		'password' => [
			'regexp' => '/^.{6,255}$/',
			'message' => 'Пароль должен состоять от 6 до 255 символов'
		],
		'email' => [
			'filter' => FILTER_VALIDATE_EMAIL,
			'message' => 'Не верный формат почты'
		],
		'dob' => [
			'regexp' => '/^(\d{4})-(\d{2})-(\d{2})$/',
			'message' => 'Дата рождения должна быть в формате YYYY-MM-DD'
		],
		'phone' => [
			'regexp' => '/(^\+\d{11})?$/',
			'message' => 'Номер телефона должен быть в международном формате (например +77568462846)'
		],
		'gender' => [
			'regexp' => '/^(\d{1})?$/',
			'message' => 'Пол нужно указывать числом (1 - мальчик, 2 - девочка)'
		],
		'age_min' => [
			'regexp' => '/^(\d+)?$/',
			'message' => 'Мининальный возраст должен быть числом'
		],
		'age_max' => [
			'regexp' => '/^(\d+)?$/',
			'message' => 'Максимальный возраст должен быть числом'
		]
	];

	/**
	 * @return array
	 * @throws \Core\Exception
	 */
	public function addAction(): array
	{
		$this->validate([
			'name',
			'password',
			'email',
			'dob',
			'gender',
			'phone'
		]);

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
		$this->validate([
			'name',
			'password',
			'email',
			'dob',
			'gender',
			'phone'
		]);

		$id 				= $this->getCurrentUser()['id'];
		$name 			= $this->name;
		$password 	= $this->password;
		$email 			= $this->email;
		$dob 				= (new \DateTime($this->dob))->format('Y-m-d');
		$gender 		= $this->gender | 0;
		$phone 			= $this->phone;

		$model = new \Model\User();

		$model->edit($id, $name, $password, $email, $dob, $gender, $phone);
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

		$model->delete($id);
	}

	/**
	 * @return array
	 * @throws \Core\Exception
	 */
	public function listAction()
	{
		$this->validate([
			'gender',
			'age_min',
			'age_max'
		]);

		$gender = $this->gender;
		$ageMin = $this->age_min;
		$ageMax = $this->age_max;

		$model = new \Model\User();

		return $model->getUsersByFilter($gender, $ageMin, $ageMax);
	}

}