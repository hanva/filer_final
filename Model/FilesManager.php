<?php

require_once 'Cool/DBManager.php';

class FilesManager
{
    public function createFolder($username)
    {
        mkdir("./files/" . $username);
    }
    public function seeFiles($username)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {

            if (is_file($dir . $value) === true) {
                array_push($data, $value);
            };
        }
        return $data;
    }
    public function seeFilesPaths($username)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_file($dir . $value) === true) {
                array_push($data, $dir . $value);
            }
        }
        return $data;
    }
    public function seeFolder($username)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_file($dir . $value) === false) {
                array_push($data, $value);
            }

        }
        return $data;
    }
    public function addFile($files, $title, $ext)
    {
        if ($title !== "") {
            $files = $title . "." . $ext;
        }
        $username = $_SESSION['username'];
        $uploaddir = './files/' . $username . '/';
        $uploadfile = $uploaddir . basename($files);
        move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
        var_dump($_FILES['userfile']['tmp_name']);
    }
}
