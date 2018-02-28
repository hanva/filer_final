<?php

require_once('Cool/BaseController.php');
require_once('Model/LoginManager.php'); 

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
        return $this->render('register.html.twig');
    }
}
