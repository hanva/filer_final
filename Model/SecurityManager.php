<?php
require_once 'Cool/DBManager.php';
class SecurityManager
{
    public function write($username)
    {
        $log = fopen('log.txt', 'a,b');
        $date = date('l jS \of F Y h:i:s A');
        $content = file_get_contents('log.txt');
        $newline = $date . ' attack by : ' . $username . "\n";
        $content = $content . $newline . "\n";
        fwrite($log, $content);
        fclose($log);
    }
}
