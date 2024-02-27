<?php

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver\Middleware;
use Doctrine\DBAL\Driver;

class SqliteRegexMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new SqliteRegexDriver($driver);
    }
}