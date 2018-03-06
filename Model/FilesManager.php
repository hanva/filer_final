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
    }
    public function deleteFile($path, $file)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        unlink($dir . $file);
    }

    public function deleteFolder($path, $file)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $file;
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir . "/" . $object) == "dir") {
                    $diro = ($file . '/' . $object);
                    $filesManager = new FilesManager();
                    $filesManager->deleteFolder($path, $diro);
                    rmdir($dir . "/" . $object);
                } else {
                    unlink($dir . "/" . $object);
                }
            }
        }
        rmdir($dir);
    }
    public function addFolder($username, $data)
    {
        $count = 1;
        foreach ($data as $value) {
            $count++;
        }
        mkdir("./files/" . $username . "/folder" . $count);
    }
    public function rename($data, $ext, $olddata)
    {
        $dir = './files/' . $_SESSION['username'] . '/';
        if (strlen($ext) === 0) {
            rename($dir . $olddata, $dir . $data);
        } else {
            rename($dir . $olddata, $dir . $data . "." . $ext);
        }
    }
}
