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
        $dir = './files/' . $_SESSION['username'] . '/' . $path . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_file($dir . $value) === true) {
                array_push($data, $value);
            };
        }
        return $data;
    }
    public function isValidToSee($array, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path . '/';
        foreach ($array as $value) {
            $type = mime_content_type($dir . $value);
            $type = substr($type, 0, strpos($type, "/"));
            if ($type === "video" or $type === "image" or $type === "text" or $type === "audio") {
                if ($type === "text") {
                    array_push($data, "text");
                } else {
                    array_push($data, "valid");
                }
            } else {
                array_push($data, "invalid");
            }
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
    public function changeText($content, $name, $path)
    {
        $newText = fopen($dir = './files/' . $_SESSION['username'] . '/' . $path . $name, 'w');
        fwrite($newText, $content);
        fclose($newText);
    }
    public function getType($name, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        array_push($data, $dir . $name);
        $type = mime_content_type($dir . $name);
        $type = substr($type, 0, strpos($type, "/"));
        if ($type === "text") {
            array_push($data, "text");
            $text = (file_get_contents($dir . $name));
            array_push($data, $text);
        } elseif ($type === "video") {
            array_push($data, "video");
        } elseif ($type === "audio") {
            array_push($data, "audio");
        } else {
            array_push($data, "image");
        }
        return $data;
    }
    public function seeFolder($username, $path)
    {
        $data = [];
        $dir = './files/' . $_SESSION['username'] . '/' . $path . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_file($dir . $value) === false) {
                array_push($data, $value);
            }
        }
        return $data;
    }
    public function seeAllFolder($username, $path, $data, $name)
    {
        $dir = './files/' . $_SESSION['username'] . $path . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_dir($dir . $value)) {
                $filesManager = new FilesManager();
                $data = $filesManager->seeAllFolder($username, $path . '/' . $value, $data, $name);
                if ($value !== $name) {
                    array_push($data, $value);
                }
            }
        }
        return $data;
    }
    public function Pathfor($username, $path, $finalfolder, $data)
    {
        $dir = './files/' . $_SESSION['username'] . $path . '/';
        $files = array_diff(scandir($dir), array(".", ".."));
        foreach ($files as $value) {
            if (is_dir($dir . $value)) {
                $filesManager = new FilesManager();
                $data = $filesManager->Pathfor($username, $path . '/' . $value, $finalfolder, $data);
                array_push($data, $dir . $value);
            }
        }
        return $data;
    }
    public function getParentPath($path)
    {
        $parentpath = "";
        $pieces = explode("/", $path);
        array_pop($pieces);
        foreach ($pieces as $piece) {
            $parentpath = $parentpath . '/' . $piece;
        }
        $parentpath = substr($parentpath, 1);
        return $parentpath;
    }
    public function addFile($path, $files, $title, $ext)
    {
        if ($title !== "") {
            $files = $title . "." . $ext;
        }
        $username = $_SESSION['username'];
        $uploaddir = './files/' . $username . '/' . $path;
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
        $dir = './files/' . $_SESSION['username'] . '/' . $path . '/' . $file;
        $objects = array_diff(scandir($dir), array(".", ".."));
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
    public function addFolder($path, $username, $data)
    {
        $count = 1;
        foreach ($data as $value) {
            $count++;
        }
        if (file_exists("./files/" . $username . '/' . $path . "/folder" . $count)) {
            $count++;
            mkdir("./files/" . $username . '/' . $path . "/folder" . $count);
        } else {
            mkdir("./files/" . $username . '/' . $path . "/folder" . $count);}
    }
    public function rename($data, $ext, $olddata, $path)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        if (strlen($ext) === 0) {
            rename($dir . $olddata, $dir . $data);
        } else {
            rename($dir . $olddata, $dir . $data . "." . $ext);
        }
    }
    public function moveInto($finalfolder, $path, $name, $folderpath)
    {
        $dir = './files/' . $_SESSION['username'] . '/' . $path;
        if (pathinfo($name, PATHINFO_EXTENSION) !== "") {
            copy($dir . $name, $folderpath . '/' . $name);
            unlink($dir . $name);
        } else {
            mkdir($folderpath . '/' . $name);
            $objects = array_diff(scandir($dir . $name), array(".", ".."));
            if (!empty($objects)) {
                foreach ($objects as $object) {
                    $filesManager = new FilesManager();
                    $filesManager->moveInto($finalfolder, $path . $name . '/', $object, $folderpath . '/' . $name);
                }
            }
        }
        if ($dir !== './files/' . $_SESSION['username'] . '/') {
            rmdir($dir . $name);
        } else {
            rmdir('./files/' . $_SESSION['username'] . '/' . $name);
        }
    }
}
