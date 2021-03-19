<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\AnotherDummyEntity;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;

/**
 * Class AnotherDummyEntityFixture
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture
 * @author     Nicolas Guilloux <nicolas.guilloux@rich-id.fr>
 * @copyright  2014 - 2021 RichID (https://www.rich-id.fr)
 */
final class AnotherDummyEntityFixture extends AbstractFixture implements DependentFixtureInterface
{
    protected function loadFixtures(): void
    {
        foreach (range(1, 20) as $index) {
            $reference = 'number-' . $index;
            $dummyEntity = $this->getReference(DummyEntity::class, $reference);

            $this->createObject(AnotherDummyEntity::class, $reference, [
                'dummyEntity' => $dummyEntity,
            ]);
        }
    }

    public function getDependencies(): array
    {
        return [
            DummyEntityFixture::class
        ];
    }
}
