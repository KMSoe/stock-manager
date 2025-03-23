<?php

namespace App\Helpers;

class HTTP
{
    static function redirect($path, $query = "")
    {
        $url = $path;
        if($query) $url .= "?$query";

        header("location: $url");
        exit();
    }
}
