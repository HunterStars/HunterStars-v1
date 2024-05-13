<?php

namespace HS\config;

class APP_DB
{
    const NAME = '';
    const HOST = '';
    const CHARSET = 'utf8mb4';
    const ACCOUNTS = [
        //new
        'auth' => ['caller', ''], //[UserName, Password]
        'novel' => ['caller', ''],
        'user' => ['caller', ''],

        //old
        'root' => ['root', ''],
        'circle' => ['caller', ''],
        'project' => ['caller', ''],
        'entry' => ['caller', ''],
        'image' => ['caller', ''],
        'catalog' => ['caller', ''],
        'tracking' => ['caller', '']
    ];
}

enum DBAccount
{
    //new
    case auth;
    case novel;
    case user;

    //old
    case root;
    case circle;
    case project;
    case entry;
    case image;
    case catalog;
    case tracking;
}
