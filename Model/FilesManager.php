<?php

require_once 'Cool/DBManager.php';

class FilesManager
{
    public function createFiles($username)
    {
        mkdir("./files/" . $username);
    }
}
