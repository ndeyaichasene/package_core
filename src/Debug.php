<?php

namespace Aicha\core;
class Debug
{
    private function __construct(){}
    
    public static function dd(mixed $data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        die;
    }
    public static function dump(mixed $data)
    {
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
        
    }
}

