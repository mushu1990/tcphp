<?php
/*
数据库配置文件，支持多数据库配置
*/

return array(
    'default'=>array(
        'hostname' => 'localhost',
        'port' => 3306,
        'database' => 'tcphpcms',
        'username' => 'root',
        'password' => 'root',
        'tablepre' => '',
        'charset' => 'utf8',
        'type' => 'mysql',
        'debug' => true,
        'pconnect' => 0,
        'autoconnect' => 0
        ),
    'default1' => array (
        'hostname' => 'localhost',
        'port' => 3306,
        'database' => 'phpcmsv9',
        'username' => '',
        'password' => '',
        'tablepre' => 'v9_',
        'charset' => 'utf8',
        'type' => 'mysql',
        'debug' => true,
        'pconnect' => 0,
        'autoconnect' => 0
        ),




    );