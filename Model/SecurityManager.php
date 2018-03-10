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
    public function securePath($path, $username)
    {
        if ($path === "") {
            return true;
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
