<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection\PrependConfiguration;

use RichCongress\BundleToolbox\Configuration\PrependConfiguration\AbstractPrependConfiguration;
use RichCongress\RecurrentFixturesTestBundle\Doctrine\Functions\Mysql\Regexp;
use RichCongress\RecurrentFixturesTestBundle\Doctrine\Functions\Mysql\RegexReplace;

class DoctrinePrependConfiguration extends AbstractPrependConfiguration
{
    public const DEFAULT_DB_PATH = '%kernel.cache_dir%/__DBNAME__.db';

    protected function prepend(): void
    {
        $this->prependExtensionConfig(
            'doctrine',
            [
                'dbal' => [
                    'default_connection' => 'default',
                    'connections'        => [
                        'default'        => [
                            'driver' => 'pdo_sqlite',
                            'user'   => 'test',
                            'path'   => static::DEFAULT_DB_PATH,
                            'url'    => null,
                            'memory' => false,
                        ],
                        'empty_database' => [
                            'driver' => 'pdo_sqlite',
                            'user'   => 'test',
                            'path'   => str_replace('.db', '.empty.db', static::DEFAULT_DB_PATH),
                            'url'    => null,
                            'memory' => false,
                        ],
                    ],
                ],
                'orm'  => [
                    'default_entity_manager' => 'default',
                    'entity_managers'        => [
                        'default'        => [
                            'connection'   => 'default',
                            'auto_mapping' => true,
                            'dql'                    => [
                                'string_functions' => [
                                    'REGEXP'         => Regexp::class,
                                    'REGEXP_REPLACE' => RegexReplace::class,
                                ],
                            ],
                        ],
                        'empty_database' => [
                            'connection' => 'empty_database',
                            'dql'                    => [
                                'string_functions' => [
                                    'REGEXP'         => Regexp::class,
                                    'REGEXP_REPLACE' => RegexReplace::class,
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );
    }
}
