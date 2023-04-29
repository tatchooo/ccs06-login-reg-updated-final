<?php

namespace App;

use PDO;

class User
{
	protected $id;
	protected $first_name;
	protected $last_name;
	protected $email;
	protected $pass;
	protected $created_at;

	public function getId()
	{
		return $this->id;
	}

	public function getFullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

	public function getFirstName()
	{
		return $this->first_name;
	}

	public function getLastName()
	{
		return $this->last_name;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public static function getById($id)
	{
		global $conn;

		try {
			$sql = "
				SELECT * FROM users
				WHERE id=:id
				LIMIT 1
			";
			$statement = $conn->prepare($sql);
			$statement->execute([
				'id' => $id
			]);
			$result = $statement->fetchObject('App\User');
			return $result;
		} catch (PDOException $e) {
			error_log($e->getMessage());
		}

		return null;
	}

	public static function attemptLogin($email, $pass)
	{
		global $conn;

		try {
			$sql = "
				SELECT * FROM users
				WHERE email=:email
					AND pass=:pass
				LIMIT 1
			";
			$statement = $conn->prepare($sql);
			$statement->execute([
				'email' => $email,
				'pass' => $pass
			]);
			$result = $statement->fetchObject('App\User');
			return $result;
		} catch (PDOException $e) {
			error_log($e->getMessage());
		}

		return null;
	}

	public static function register($first_name, $last_name, $email, $password)
	{
		global $conn;

		try {
			$sql = "
				INSERT INTO users (first_name, last_name, email, pass)
				VALUES ('$first_name', '$last_name', '$email', '$password')
			";
			$conn->exec($sql);
			// echo "<li>Executed SQL query " . $sql;
			return $conn->lastInsertId();
		} catch (PDOException $e) {
			error_log($e->getMessage());
		}

		return false;
	}

	public static function registerMany($users)
	{
		global $conn;

		try {
			foreach ($users as $user) {
				$sql = "
					INSERT INTO users
					SET
						first_name=\"{$user['first_name']}\",
						last_name=\"{$user['last_name']}\",
						email=\"{$user['email']}\",
						pass=\"{$user['pass']}\"
				";
				$conn->exec($sql);
				// echo "<li>Executed SQL query " . $sql;
			}
			return true;
		} catch (PDOException $e) {
			error_log($e->getMessage());
		}

		return false;
	}	
}