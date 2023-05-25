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

    public function checkUsernameExists($username)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE user_login = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();

        $result = $statement->fetch();
        $count = $result['count'];

        return $count > 0;
    }
}