<?php

namespace Model;

use Core\Model;

class User extends Model
{

	public function add(string $name, string $password, string  $email, string $dob, int $gender, $phone, string $ip)
	{
		$token = $this->_generateToken();

		$this->query("
			INSERT INTO `users` (`name`, `email`, `password`, `dob`, `gender`, `phone`, `ip`, `token`)
			VALUES
		 (:name, :email, :password, :dob, :gender, :phone, :ip, :token);
	 	", [
	 		[':name', $name, SQLITE3_TEXT],
			[':email', $email, SQLITE3_TEXT],
			[':password', $password, SQLITE3_TEXT],
			[':dob', $dob, SQLITE3_TEXT],
			[':gender', $gender, SQLITE3_INTEGER],
			[':phone', $phone, SQLITE3_TEXT],
			[':ip', $ip, SQLITE3_TEXT],
			[':token', $token, SQLITE3_TEXT]
		]);

		return [
			'access_token' => $token
		];
	}

	public function getUserByToken(string $token)
	{
		$result = $this->query("
			SELECT * FROM `users` WHERE `token` = :token
		", [
			[':token', $token, SQLITE3_TEXT]
		]);

		return $result;
	}

	private function _generateToken()
	{
		return bin2hex(openssl_random_pseudo_bytes(64));
	}

}