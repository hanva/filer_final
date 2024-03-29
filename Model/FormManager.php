<?php

require_once 'Cool/DBManager.php';

class FormManager
{
    public function CheckUsername($username)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->query("SELECT username FROM users");
        $posts = $result->fetchAll(PDO::FETCH_COLUMN, 0);
        foreach ($posts as $value) {
            if ($value === $username) {
                return false;
            }
        }
        return true;
    }
    public function CheckPassword($username, $password)
    {
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->query("SELECT password FROM users where username = '$username'");
        $posts = $result->fetch(PDO::FETCH_COLUMN, 0);
        if ($posts === $password) {
            $_SESSION['username'] = $username;
            return true;
        }
        return false;
    }
    public function Register($firstname, $lastname, $username, $email, $password, $password_repeat)
    {
        $dataerrors = [];
        if (strlen($username) < 4) {
            array_push($dataerrors, "4 letters needed for an username");
        }
        if (strlen($firstname) < 1) {
            array_push($dataerrors, "Enter a first name");
        }
        if (strlen($lastname) < 1) {
            array_push($dataerrors, "Enter a last name");
        }
        if (strlen($password) < 6) {
            array_push($dataerrors, "Password too short(min6)");
        }
        if ($password !== $password_repeat) {
            array_push($dataerrors, "Passwords does not match");
        }
        if ($dataerrors !== []) {
            return $dataerrors;
        } else {

            $dbm = DBManager::getInstance();
            $pdo = $dbm->getPdo();
            $result = $pdo->prepare('INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `username`) VALUES (NULL, :firstname, :lastname, :email, :password, :username)');
            $result->bindParam(':firstname', $firstname);
            $result->bindParam(':lastname', $lastname);
            $result->bindParam(':email', $email);
            $result->bindParam(':password', $password);
            $result->bindParam(':username', $username);
            $result->execute();
            return true;
        }
    }
}
