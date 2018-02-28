<?php

require_once('Cool/DBManager.php');

class FormManager
{
    public function CheckUsername($username){   
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->query("SELECT username FROM users");
         $posts = $result->fetchAll(PDO::FETCH_COLUMN, 0);
        foreach ($posts as $value) {
            if ($value === $username){
            return false;
            }          
        }
        return true;
    }
    public function Register($firstname,$lastname,$username,  $email, $password, $password_repeat)
    {
    $dataerrors = [];
        if(strlen($username) < 4) {
            $dataerrors[]="4 letters needed for an username";
        }
        elseif(strlen($firstname) < 1) {
            $dataerrors[]="Enter a first name";
        }
        elseif(strlen($lastname) < 1) {
            $dataerrors[]="Enter a last name";
        }
        elseif(strlen($password)<6){
            $dataerrors[]="Password too short(min6)";
        }
        elseif ($password !== $password_repeat)
        {
            $dataerrors[]="Passwords don't match";
        }
        else{
            
        $dbm = DBManager::getInstance();
        $pdo = $dbm->getPdo();
        $result = $pdo->prepare('INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `username`) VALUES (NULL, :firstname, :lastname, :email, :password, :username)');
        $result->bindParam(':firstname', $firstname);
        $result->bindParam(':lastname', $lastname);
        $result->bindParam(':email', $email);
        $result->bindParam(':password', $password);
        $result->bindParam(':username', $username);
        $result->execute();
        header('Location: ?action=login');
        exit();
        }
            return $dataerrors;
    }
}