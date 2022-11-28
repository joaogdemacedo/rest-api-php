<?php

namespace App\Utils;

use App\Plugins\Db\Connection\Mysql;
use App\Plugins\Db\Db;

class Utils
{
    public static function getDbConnection(): Db
    {
        $connection = new Mysql('127.0.0.1:8889','catering_facilities','root','root');
        return new Db($connection);
    }

    public static function getDecodeJson()
    {
        $getBody = file_get_contents('php://input');
        return json_decode($getBody, true);
    }
}