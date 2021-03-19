<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Manager;

use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureClassNotFound;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerAlreadyInitialized;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerNotInitialized;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureReferenceNotFound;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository\DummyEntityRepository;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use RichCongress\WebTestBundle\TestCase\TestCase;

/**
 * Class FixtureManagerTest
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Manager
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture
 * @covers \RichCongress\RecurrentFixturesTestBundle\Manager\AbstractORMFixtureManager
 * @covers \RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager
 * @covers \RichCongress\RecurrentFixturesTestBundle\Exception\FixtureClassNotFound
 * @covers \RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerAlreadyInitialized
 * @covers \RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerNotInitialized
 * @covers \RichCongress\RecurrentFixturesTestBundle\Exception\FixtureReferenceNotFound
 * @TestConfig("fixtures")
 */
final class FixtureManagerTest extends TestCase
{
    /** @var FixtureManager */
    public $fixtureManager;

    protected function afterTest(): void
    {
        if (!$this->fixtureManager->isInitialized()) {
            $this->fixtureManager->init();
        }
    }

    /** @group test */
    public function testInit(): void
    {
        $this->fixtureManager->reset();
        self::assertFalse($this->fixtureManager->isInitialized());

        $this->fixtureManager->init();
        self::assertTrue($this->fixtureManager->isInitialized());
    }

    public function testAlreadyInitialized(): void
    {
        $this->expectException(FixtureManagerAlreadyInitialized::class);

        $this->fixtureManager->init();
    }

    public function testGetReference(): void
    {
        $fixture = $this->fixtureManager->getReference(DummyEntity::class, 'number-2');

        self::assertInstanceOf(DummyEntity::class, $fixture);
        self::assertSame(2, $fixture->getIndex());
    }

    public function testGetReferenceNotInitialized(): void
    {
        $this->expectException(FixtureManagerNotInitialized::class);

        $this->fixtureManager->reset();
        $this->fixtureManager->getReference(DummyEntity::class, 'number-2');
    }

    public function testGetUnknownReferenceFromTheSameEntity(): void
    {
        $this->expectException(FixtureReferenceNotFound::class);
        $this->expectExceptionMessage('The reference "nummber-2" for the class "RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity" does not exist. Did you mean "number-2"?');

        $this->fixtureManager->getReference(DummyEntity::class, 'nummber-2');
    }

    public function testGetReferenceForUnknownEntity(): void
    {
        $this->expectException(FixtureClassNotFound::class);
        $this->expectExceptionMessage('No fixture found for the class "RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository\DummyEntityRepository".');

        $this->fixtureManager->getReference(DummyEntityRepository::class, 'nummber-2');
    }
}
