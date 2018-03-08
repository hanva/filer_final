<?php

require_once 'Cool/BaseController.php';
require_once 'Model/FormManager.php';
require_once 'Model/FilesManager.php';
session_start();
class MainController extends BaseController
{
    public function homeAction()
    {
        global $path;
        $data = [];
        if (empty($_GET['path']) === false) {
            $path = $_GET['path'];
        }
        $parentpath = rtrim($path, "/");
        if (empty($_SESSION['username']) === false) {
            if (empty($_GET['deletefile']) === false) {
                $data = $_GET['deletefile'];
                $filesManager = new FilesManager();
                if (pathinfo($data, PATHINFO_EXTENSION) === "") {
                    $filesManager->deleteFolder($path, $data);
                } else {
                    $filesManager->deleteFile($path, $data);
                }
                if (strlen($path) > 1) {
                    return $this->redirectToRoute('home' . '&path=' . $path);
                } else {
                    return $this->redirectToRoute('home');
                }
            }
            $filesManager = new FilesManager();
            $fileresponse = $filesManager->seeFiles($_SESSION['username'], $path);
            $pathResponse = $filesManager->seeFilesPaths($_SESSION['username'], $path);
            $folderResponse = $filesManager->seeFolder($_SESSION['username'], $path);
            if (strlen($path) > 1) {
                if ($path{strlen($path) - 1} !== '/') {
                    $path = $path . '/';
                }
            }
            $data = [
                'username' => $_SESSION['username'],
                'files' => $fileresponse,
                'paths' => $pathResponse,
                'folders' => $folderResponse,
                'path' => $path,
                'parentpath', $parentpath,
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
    public function renameAction()
    {
        global $path;
        if (empty($_GET['path']) === false) {
            $path = $_GET['path'];
        }
        if (isset($_POST['newname'])) {
            if (!empty($_POST['newname'] === true)) {
            } else {
                $ext = pathinfo($_POST['oldname'], PATHINFO_EXTENSION);
                $olddata = $_POST['oldname'];
                $data = $_POST['newname'];
                if (empty($_POST['path']) === false) {
                    $path = $_POST['path'];
                }
                $filesManager = new FilesManager();
                $filesManager->rename($data, $ext, $olddata, $path);
                if (strlen($path) > 1) {
                    return $this->redirectToRoute('home' . '&path=' . $path);
                } else {
                    return $this->redirectToRoute('home');
                }
            }
        }
        if (empty($_GET['name']) === true) {
            return $this->redirectToRoute('home');
        } else {
            $data = [
                'file' => $_GET['name'],
                'username' => $_SESSION['username'],
                'path' => $path,
            ];
            return $this->render('rename.html.twig', $data);
        }
    }
    public function addfileAction()
    {

        global $path;
        if (empty($_GET['path']) === false) {
            $path = $_GET['path'];
        }
        if (!empty($_SESSION['username']) === false) {
            return $this->redirectToRoute('home');
        }
        if (!empty($_FILES)) {
            $filesManager = new FilesManager();
            $files = $_FILES['userfile']['name'];
            $title = $_POST['usertitle'];
            $ext = pathinfo($files, PATHINFO_EXTENSION);
            $path = $_POST['path'];
            $filesManager->addFile($path, $files, $title, $ext);
            if (strlen($path) > 1) {
                return $this->redirectToRoute('home' . '&path=' . $path);
            } else {
                return $this->redirectToRoute('home');
            }
        }
        $data = [
            'username' => $_SESSION['username'],
            'path' => $path,
        ];
        return $this->render('addfile.html.twig', $data);
    }
    public function moveAction()
    {
        global $path;
        $filesManager = new FilesManager();
        $data = [];
        if (empty($_POST['folders']) === false) {
            $name = $_POST['name'];
            $finalfolder = $_POST['folders'];
            if ($finalfolder === 'home') {
                $folderpath = './files/' . $_SESSION['username'];
                $path = $_POST['path'];
                $filesManager->moveInto($finalfolder, $path, $name, $folderpath);
                return $this->redirectToRoute('home');
            }
            $folderpath = $filesManager->Pathfor($_SESSION['username'], $path, $finalfolder, $data);
            foreach ($folderpath as $value) {
                if (basename($value) === $finalfolder) {
                    $folderpath = $value;
                }
            }
            $path = $_POST['path'];
            $filesManager->moveInto($finalfolder, $path, $name, $folderpath);
            return $this->redirectToRoute('home');
        } else {
            $name = $_GET['name'];
            $filesManager = new FilesManager();
            $folders = $filesManager->seeAllFolder($_SESSION['username'], $path, $data, $name);
            if (empty($_GET['path']) === false) {
                $path = $_GET['path'];
            }
            $data = [
                'username' => $_SESSION['username'],
                'file' => $_GET['name'],
                'path' => $path,
                'folders' => $folders,
            ];
            if (empty($_GET['path']) === false) {
                $home = "";
                $data['home'] = $home;
            }
        }
        return $this->render('move.html.twig', $data);
    }
    public function addfolderAction()
    {
        global $path;
        if (empty($_GET['path']) === false) {
            $path = $_GET['path'];
        }
        $filesManager = new FilesManager();
        $data = $filesManager->seeFolder($_SESSION['username'], $path);
        $filesManager->addFolder($path, $_SESSION['username'], $data);
        $path = substr($path, 0, -1);
        if (strlen($path) > 1) {
            return $this->redirectToRoute('home' . '&path=' . $path);
        } else {
            return $this->redirectToRoute('home');
        }

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
