# Changelog

## Version 0.1.12

- Open bundle to doctrine/dbal v2.x and v3.x

## Version 0.1.11

- Fix dependency to force doctrine/dbal v2, this lib is not compatible with v3 yet

## Version 0.1.10

- Fix entity manager remove

## Version 0.1.9

- Add REGEXP and REGEXP_REPLACE in SQLite

## Version 0.1.8

- Add FixtureGenerator in AbstractFixture

## Version 0.1.7

- Autoconfigure doctrine

## Version 0.1.6

- Warn authentication failure when user has no role

## Version 0.1.5

- Add TestAuthenticator

## Version 0.1.4

- Add flush step on each fixtures to make sure that any dependency reference has an id

## Version 0.1.3

- Fix a major bug around the fixtures where it failed to recognize entities

## Version 0.1.2

- Rewrite `getReference` and `setReference` in `AbstraxtFixtures` to support the new way of declaring references

## Version 0.1.1

- Fix composer dependencies
