<?php 
require_once('Cool/DBManager.php'); 

class LoginManager

{   public function CheckUsername($username){   
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
    public function Login($username,$password)
    {
       $dataerrors = []; 

       if ($username == '' || $password ==''){
           $dataerrors[] = "Some fields are empty"; 
       }
       elseif($username === NULL)
       {
            $dataerrors[] = "Invalid username or password"; 
       }else{
            
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


        
    }; 





