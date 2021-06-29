<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\AnotherDummyEntity;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;

final class UnorganizedDummyFixture extends AbstractFixture implements  DependentFixtureInterface
{
    protected function loadFixtures(): void
    {
        foreach (range(11, 20) as $index) {
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
