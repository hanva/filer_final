<?php

require_once('Cool/BaseController.php');
require_once('Model/FormManager.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }
    public function loginAction()
    {
        return $this->render('login.html.twig');
    }

    public function registerAction()
    {
    if (!empty($_POST['firstname']) && !empty($_POST['lastname'])
    && !empty($_POST['username']) && !empty($_POST['email'])
    && !empty($_POST['password']) && !empty($_POST['password_repeat']))
    {
    $firstname = htmlentities($_POST['firstname']);
    $lastname = htmlentities($_POST['lastname']);
    $username = htmlentities($_POST['username']);
    $email = htmlentities($_POST['email']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    $formManager = new FormManager();
    if($formManager->CheckUsername($username)===true){
        $formManager->Register($firstname,$lastname,$username,  $email, $password, $password_repeat);
    }
    else{
        return $this->redirectToRoute('register');
        } 
    }
        else{ 
        return $this->render('register.html.twig');
        } 
    }
}
