<?php
require_once 'Cool/DBManager.php';
class SecurityManager
{
    public function write($username)
    {
        $log = fopen('log.txt', 'a,b');
        $date = date('l jS \of F Y h:i:s A');
        $newline = $date . ' attack by : ' . $username . "\n";
        fwrite($log, $newline);
        fclose($log);
    }
    public function securePath($path, $username, $fold)
    {
        if ($path === "") {
            return true;
        }
        $dir = './files/' . $_SESSION['username'] . '/' . $path . '/';

        if ($fold === 1) {
            if (file_exists($dir) === false) {
                return false;
            }
        }
        $a = '../';
        if (strpos($path, $a) !== false) {
            $securityManager = new SecurityManager();
            $securityManager->write($username);
            return false;
        }
        return true;
    }
}
