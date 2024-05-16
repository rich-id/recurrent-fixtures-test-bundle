<?php

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Platforms\SqlitePlatform;

class SqliteRegexMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return $driver->getDatabasePlatform() instanceof SqlitePlatform
            ? new SqliteRegexDriver($driver)
            : $driver;
    }
}