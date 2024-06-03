<?php

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\SqlitePlatform;

class SqliteRegexMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        $provider = \class_exists('\Doctrine\DBAL\Connection\StaticServerVersionProvider')
            ? $driver->getDatabasePlatform(new \Doctrine\DBAL\Connection\StaticServerVersionProvider("")) // DBAL 4
            : $driver->getDatabasePlatform();                                                             // DBAL 3

        return $provider instanceof SqlitePlatform
            ? new SqliteRegexDriver($driver)
            : $driver;
    }
}
