<?php

namespace RichCongress\RecurrentFixturesTestBundle\Tests\CompilerPass;

use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyUser;
use RichCongress\TestFramework\TestConfiguration\Attribute\TestConfig;


/**
 * @covers \RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass\DoctrineMappingPass
 */
class AutoMappingTest extends TestCase
{
    #[TestConfig('fixtures')]
    public function testCanPersistWithFixturesDatabase(): void
    {
        $user = new DummyUser();
        $this->getManager()->persist($user);
        $this->getManager()->flush();

        self::assertEquals(2, $user->getId());
    }

    #[TestConfig('kernel')]
    public function testCanPersistWithEmptyDatabase(): void
    {
        $user = new DummyUser();
        $this->getManager()->persist($user);
        $this->getManager()->flush();

        self::assertEquals(1, $user->getId());
    }
}
