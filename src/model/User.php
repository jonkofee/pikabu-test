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

		return $token;
	}

	public function edit(int $id, string $name, string $password, string  $email, string $dob, int $gender, $phone)
	{
		$this->query("
			UPDATE `users` 
			SET `name` = :name,
					`password` = :password,
					`email` = :email,
					`dob` = :dob,
					`gender` = :gender,
					`phone` = :phone
			WHERE `id` = :id;
	 	", [
			[':name', $name, SQLITE3_TEXT],
			[':password', $password, SQLITE3_TEXT],
			[':email', $email, SQLITE3_TEXT],
			[':dob', $dob, SQLITE3_TEXT],
			[':gender', $gender, SQLITE3_INTEGER],
			[':phone', $phone, SQLITE3_TEXT],
			[':id', $id, SQLITE3_INTEGER]
		]);
	}

	public function delete(int $id)
	{
		$this->query("
			DELETE FROM `users`
			WHERE `id` = :id;
	 	", [
			[':id', $id, SQLITE3_INTEGER]
		]);
	}

	public function getUserByToken(string $token)
	{
		$result = $this->query("
			SELECT * FROM `users` WHERE `token` = :token
		", [
			[':token', $token, SQLITE3_TEXT]
		]);

		return $result->fetchArray(SQLITE3_ASSOC);
	}

	public function getUsersByFilter($gender, $ageMin, $ageMax)
	{
		$queryStr = "SELECT id, name, gender, cast(strftime('%Y.%m%d', '2019-04-15') - strftime('%Y.%m%d', dob) as int) as age FROM users";

		$filters = [];

		if (!is_null($gender)) {
			$filters[] = '`gender` = :gender';
		}
		if (!is_null($ageMin)) {
			$filters[] = '`age` >= :ageMin';
		}
		if (!is_null($ageMax)) {
			$filters[] = '`age` < :ageMax';
		}

		if ($filters) {
			$queryStr .= " WHERE " . implode(' AND ', $filters);
		}

		$result = $this->query($queryStr, [
			[':gender', $gender, SQLITE3_INTEGER],
			[':ageMin', $ageMin, SQLITE3_INTEGER],
			[':ageMax', $ageMax, SQLITE3_INTEGER]
		]);

		$data = [];

		while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
			$data[] = $row;
		}

		return $data;
	}

	private function _generateToken()
	{
		return bin2hex(openssl_random_pseudo_bytes(64));
	}

}