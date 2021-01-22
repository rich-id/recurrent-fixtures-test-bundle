<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Helper;

use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\TestTools\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper;

/**
 * Class ReferenceNameHelperTest
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Helper
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @covers \RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper
 */
final class ReferenceNameHelperTest extends TestCase
{
    public function testCannotInstanciateHelper(): void
    {
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Call to private RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper::__construct()');

        new ReferenceNameHelper();
    }

    public function testTransformWithClass(): void
    {
        $reference = ReferenceNameHelper::transform(DummyEntity::class, 'test-1');

        self::assertSame('RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity_test-1', $reference);
    }

    public function testTransformWithObject(): void
    {
        $object = new DummyEntity();
        $reference = ReferenceNameHelper::transform($object, 'test-1');

        self::assertSame('RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity_test-1', $reference);
    }

    public function testReverse(): void
    {
        $innerReference = DummyEntity::class . '_this_is_a_test';
        [$class, $reference] = ReferenceNameHelper::reverse($innerReference);

        self::assertSame(DummyEntity::class, $class);
        self::assertSame('this_is_a_test', $reference);
    }
}
