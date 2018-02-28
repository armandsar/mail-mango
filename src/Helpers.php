<?php


namespace Armandsar\MailMango;


class Helpers
{
    public function time()
    {
        return time();
    }

    public function bin2hex($bytes)
    {
        return bin2hex($bytes);
    }

    public function exec($command)
    {
        return exec($command);
    }

    public function os()
    {
        return php_uname('s');
    }
}