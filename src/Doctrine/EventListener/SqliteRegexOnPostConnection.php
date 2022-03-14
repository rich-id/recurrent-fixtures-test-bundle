<?php

declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine\EventListener;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDO;
use Doctrine\DBAL\Event\ConnectionEventArgs;

class SqliteRegexOnPostConnection
{
    public function postConnect(ConnectionEventArgs $args): void
    {
        $connection = $this->extractConnection($args->getConnection());

        if ($connection === null) {
            return;
        }

        // For doctrine/dbal 2.x
        if (\method_exists($connection, 'sqliteCreateFunction')) {
            $nativeConnection = $connection;
            // For doctrine/dbal 3.x
        } elseif (\method_exists($connection, 'getNativeConnection')) {
            $nativeConnection = $connection->getNativeConnection();
        } else {
            throw new \InvalidArgumentException('Unsupported doctrine pdo version.');
        }

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
    }

    /** @param Connection|mixed $connection */
    private function extractConnection($connection): ?PDO\Connection
    {
        if ($connection instanceof PDO\Connection) {
            return $connection;
        }

        if (!method_exists($connection, 'getWrappedConnection')) {
            return null;
        }

        $wrappedConnection = $connection->getWrappedConnection();

        return $this->extractConnection($wrappedConnection);
    }
}
