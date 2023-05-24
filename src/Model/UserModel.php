<?php

namespace App\Model;

class UserModel
{
    private $db;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    public function registerUser($username, $password, $email)
    {
        var_dump($username);
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (user_login, user_password, user_email) VALUES (:username, :password, :email)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashedPassword);
        $statement->bindParam(':email', $email);
        
        return $statement->execute();
    }

    public function checkCredentials($username, $password)
    {
        $query = "SELECT user_password FROM users WHERE user_login = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch();
        $hashedPassword = $result['user_password'];

        return password_verify($password, $hashedPassword);
    }
}
