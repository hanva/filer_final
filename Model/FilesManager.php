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
            array_push($data, $value);
        }
        return $data;
    }
    public function seePath($username)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            array_push($data, $dir . $value);
        }
        return $data;
    }
}
