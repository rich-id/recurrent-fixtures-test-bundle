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
        foreach (range(1, 50) as $index) {
            $entity = new DummyEntity();
            $entity->setIndex($index);
            $entity->setReference('number-' . $index);
            $this->save($entity, 'number-' . $index);
        }

        foreach (range(51, 100) as $index) {
            $this->createObject(DummyEntity::class, 'number-' . $index, [
                'index'     => $index,
                'reference' => 'number-' . $index,
            ]);
        }
    }
}
