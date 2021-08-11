# Exceptions

Here is a list of the exceptions the bundle might throw.


## BadDoctrineConfigurationException

This happens when Doctrine is not configured to support both a fixtures populated database and an empty one.

This bundle should automatically set this configuration for you. If this exception is thrown it's probably due to a bug in the bundle or due to a funky doctrine configuration. You can use `bin/console debug:config doctrine` to dump your configuration as understood by symfony.

As a reference, here is a sample of configuration for Doctrine for the test environment. Mind the name `empty_database` which is hardcoded for now.

```yaml
doctrine:
    dbal:
        default_connection: default
        connections:
            default:
                driver: pdo_sqlite
                user: test
                path: '%kernel.cache_dir%/__DBNAME__.db'
                url: null
                memory: true
            empty_database:
                driver: pdo_sqlite
                user: test
                path: '%kernel.cache_dir%/__DBNAME___empty.db'
                url: null
                memory: true
    orm:
        auto_generate_proxy_classes: true
        default_entity_manager: default
        entity_managers:
            default:
                naming_strategy: doctrine.orm.naming_strategy.underscore
                auto_mapping: true
                connection: default
                mappings:
                    # Your mappings
            empty_database:
                connection: empty_database
                naming_strategy: doctrine.orm.naming_strategy.underscore
                auto_mapping: false
                mappings:
                    # A copy of the same mappings from above
#
```
