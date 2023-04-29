<?php

namespace App;

use PDO;
use PDOException;

class User
{
    public static function register($first_name, $last_name, $email, $password)
    {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
        $stmt->execute(['first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)]);
        return $pdo->lastInsertId();
    }

    public static function login($email, $password)
    {
        $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            return [
                'id' => $user['id'],
                'fullname' => $user['first_name'] . ' ' . $user['last_name'],
                'email' => $user['email']
            ];
        } else {
            return false;
        }
    }
}