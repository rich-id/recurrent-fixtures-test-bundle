<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture;

use RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;

/**
 * Class DummyEntityFixture
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class DummyEntityFixture extends AbstractFixture
{
    protected function loadFixtures(): void
    {
        foreach (range(1, 20) as $index) {
            $entity = new DummyEntity();
            $entity->setIndex($index);
            $this->save($entity, 'number-' . $index);
        }
    }
}
