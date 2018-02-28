<?php

require_once('Cool/DBManager.php');

class FormManager
{
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
          //  $creation = date('Y-m-d H:i:s');
          //  mkdir("./files/".$username);
            //$structure = './files/';
            //$q = "INSERT INTO `users` (`id`, `creation`, `firstname`, `lastname`, `username`, `email`, `password`) VALUES (NULL, '".$creation."', '".$firstname."', '".$lastname."', '".$username."', '".$email."', '".$password."')";
            //mysqli_query($link, $q);
            header('Location: index.php');
            exit();
        }
        var_dump($dataerrors);
    }
}