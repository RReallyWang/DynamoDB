<?php
/**
 * Created by PhpStorm.
 * User: reallywang
 * Date: 2019/3/7
 * Time: 10:50 AM
 */

namespace ReallyWang\DynamoDB;

class DB
{
    public static $config = [];
    public static $connect;

    public static function config(array $config, string $connect = 'default')
    {
        self::$config  = $config;
        self::$connect = $connect;

    }

    public static function table(string $table)
    {
        $query = new DynamoDB(self::$connect);
        $query->table($table);
        return $query;
    }
}