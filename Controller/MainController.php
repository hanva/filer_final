<?php

require_once 'Cool/BaseController.php';
require_once 'Model/FormManager.php';
require_once 'Model/FilesManager.php';
session_start();
class MainController extends BaseController
{
    public function homeAction()
    {
        $data = [];
        if (empty($_SESSION['username']) === false) {
            $filesManager = new FilesManager();
            $fileresponse = $filesManager->seeFiles($_SESSION['username']);
            $pathResponse = $filesManager->seeFilesPaths($_SESSION['username']);
            $folderResponse = $filesManager->seeFolder($_SESSION['username']);
            $data = [
                'username' => $_SESSION['username'],
                'files' => $fileresponse,
                'paths' => $pathResponse,
                'folders' => $folderResponse,
            ];
            return $this->render('home.html.twig', $data);
        } else {
            return $this->render('home.html.twig');
        }
    }
    public function disconnectAction()
    {
        if (!empty($_SESSION['username']) === false) {
            return $this->redirectToRoute('home');
        }
        session_destroy();
        return $this->redirectToRoute('home');
    }
    public function addfileAction()
    {
        if (!empty($_SESSION['username']) === false) {
            return $this->redirectToRoute('home', $data);
        }
        if (!empty($_FILES)) {
            $filesManager = new FilesManager();
            $files = $_FILES['userfile']['name'];
            $title = $_POST['usertitle'];
            $ext = pathinfo($files, PATHINFO_EXTENSION);
            $filesManager->addFile($files, $title, $ext);
            return $this->redirectToRoute('home');
        }
        $data = [
            'username' => $_SESSION['username'],
        ];
        return $this->render('addfile.html.twig', $data);
    }
    public function addfolderAction()
    {
        $filesManager = new FilesManager();
        $data = $filesManager->seeFolder($_SESSION['username']);
        $filesManager->addFolder($_SESSION['username'], $data);
        return $this->redirectToRoute('home');
    }

    public function deletefileAction()
    {
        $data = $_GET['filename'];
        $filesManager = new FilesManager();
        $filesManager->deleteFile($data);
        return $this->redirectToRoute('home');
    }

    public function loginAction()
    {
        if (!empty($_POST['username']) && !empty($_POST['password'])) {
            $username = htmlentities($_POST['username']);
            $password = htmlentities($_POST['password']);
            $formManager = new FormManager();
            if ($formManager->CheckUsername($username) === false && $formManager->CheckPassword($username, $password)) {
                return $this->redirectToRoute('home');
            } else {
                $data = [
                    'errors' => "wrong username or password",
                ];
            }
            return $this->render('login.html.twig', $data);
        } else {
            return $this->render('login.html.twig');
        }
    }

    public function registerAction()
    {
        if (!empty($_POST['firstname']) && !empty($_POST['lastname'])
            && !empty($_POST['username']) && !empty($_POST['email'])
            && !empty($_POST['password']) && !empty($_POST['password_repeat'])) {
            $firstname = htmlentities($_POST['firstname']);
            $lastname = htmlentities($_POST['lastname']);
            $username = htmlentities($_POST['username']);
            $email = htmlentities($_POST['email']);
            $password = $_POST['password'];
            $password_repeat = $_POST['password_repeat'];
            $formManager = new FormManager();
            $usernameChecked = $formManager->CheckUsername($username);
            if ($usernameChecked === true) {
                $response = $formManager->Register($firstname, $lastname, $username, $email, $password, $password_repeat);
                if ($response === true) {
                    $filesManager = new FilesManager();
                    $filesManager->createFolder($username);
                    return $this->redirectToRoute('login');
                } else {
                    $data = [
                        'errors' => $response,
                    ];
                    return $this->render('register.html.twig', $data);
                }
            } else {
                $data = [
                    'already' => "Username already used",
                ];
                return $this->render('register.html.twig', $data);
            }
        } else {
            return $this->render('register.html.twig');
        }
    }
}
