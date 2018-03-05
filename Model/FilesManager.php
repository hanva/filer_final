<?php
require_once 'Cool/DBManager.php';
class FilesManager
{
    public function createFolder($username)
    {
        mkdir("./files/" . $username);
    }
    public function seeFiles($username, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {

            if (is_file($dir . $value) === true) {
                array_push($data, $value);
            };
        }
        return $data;
    }
    public function seeFilesPaths($username, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_file($dir . $value) === true) {
                array_push($data, $dir . $value);
            }
        }
        return $data;
    }
    public function navInto($data)
    {

    }
    public function seeFolder($username, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
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
    public function deleteFile($path, $file)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        unlink($dir . $file);
    }
    public function deleteFolder($path, $file)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        rmdir($dir . $file);
    }
    public function addFolder($username, $data)
    {
        $count = 1;
        foreach ($data as $value) {
            $count++;
        }
        mkdir("./files/" . $username . "/folder" . $count);
    }
}
