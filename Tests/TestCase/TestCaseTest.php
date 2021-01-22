<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\TestCase;

use RichCongress\RecurrentFixturesTestBundle\Exception\FixturesNotInitialized;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManagerInterface;
use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;

/**
 * Class TestCaseTest
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\RecurrentFixturesTestBundle\Exception\FixturesNotInitialized
 * @covers \RichCongress\RecurrentFixturesTestBundle\TestCase\Internal\FixtureTestCase
 * @covers \RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase
 */
final class TestCaseTest extends TestCase
{
    public function testGetReferenceWithNoConfig(): void
    {
        $this->expectException(FixturesNotInitialized::class);
        $this->getReference(DummyEntity::class, 'number-1');
    }

    /**
     * @TestConfig("kernel")
     */
    public function testGetReferenceWithWrongConfig(): void
    {
        $this->expectException(FixturesNotInitialized::class);
        $this->getReference(DummyEntity::class, 'number-1');
    }

    /**
     * @TestConfig("fixtures")
     */
    public function testGetReferenceWithConfig(): void
    {
        $result = $this->getReference(DummyEntity::class, 'number-1');
        self::assertInstanceOf(DummyEntity::class, $result);
    }

    /**
     * @TestConfig("kernel")
     */
    public function testSideEffect(): void
    {
        $fixtureManager = $this->getService(FixtureManagerInterface::class);
        $fixtureManager->reset();
        self::assertFalse($fixtureManager->isInitialized());
    }

    /**
     * @TestConfig("fixtures")
     */
    public function testGetReferenceWithConfigButFixturesNotInitialized(): void
    {
        $result = $this->getReference(DummyEntity::class, 'number-1');
        self::assertInstanceOf(DummyEntity::class, $result);
    }
}
