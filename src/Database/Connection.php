<?php

declare(strict_types=1);

namespace Tiagolopes\DesignPatterns\Database;

use PDO;

class Connection extends PDO
{
    private static ?self $instance = null;
    private function __construct()
    {
        $dsn = 'sqlite:database.sqlite3';

        parent::__construct($dsn);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }
}
