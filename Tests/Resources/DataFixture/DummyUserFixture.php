<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\DataFixture;

use RichCongress\RecurrentFixturesTestBundle\DataFixture\AbstractFixture;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyUser;

final class DummyUserFixture extends AbstractFixture
{
    protected function loadFixtures(): void
    {
        $this->createObject(DummyUser::class, 'user', []);
    }
}
