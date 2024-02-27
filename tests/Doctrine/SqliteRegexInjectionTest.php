<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Doctrine;

use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;

/** @covers \RichCongress\RecurrentFixturesTestBundle\Doctrine\EventListener\SqliteRegexOnPostConnection */
#[TestConfig('fixtures')]
final class SqliteRegexInjectionTest extends TestCase
{
    public function testRegex(): void
    {
        $qb = $this
            ->getManager()
            ->createQueryBuilder()
            ->select('de.id')
            ->from(DummyEntity::class, 'de')
            ->where('de.id < 15')
            ->andWhere("REGEXP(de.reference, 'number-\\d$') = 1");

        $result = $qb->getQuery()->getResult();

        self::assertCount(9, $result);
    }

    public function testRegexReplace(): void
    {
        $qb = $this
            ->getManager()
            ->createQueryBuilder()
            ->select('de.id')
            ->from(DummyEntity::class, 'de')
            ->where('de.id < 15')
            ->andWhere("REGEXP_REPLACE(de.reference, 'number-\\d$', 'number_1') = 'number_1'");

        $result = $qb->getQuery()->getResult();

        self::assertCount(9, $result);
    }
}
