<?php

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\Middleware;

use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;

class SqliteRegexDriver extends AbstractDriverMiddleware
{
    public function connect(array $params): Connection
    {
        $connection = parent::connect($params);
        $nativeConnection = $connection->getNativeConnection();

        $nativeConnection->sqliteCreateFunction(
            'REGEXP',
            static function (string $regex, string $value): int {

                return preg_match('/' . $regex . '/u', $value) ?: 0;
            }
        );

        $nativeConnection->sqliteCreateFunction(
            'REGEXP_REPLACE',
            static function (string $value, string $regex, string $replace): string {
                return preg_replace('/' . $regex . '/u', $replace, $value);
            }
        );

        return $connection;
    }
}