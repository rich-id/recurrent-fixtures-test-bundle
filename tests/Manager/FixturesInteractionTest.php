<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Manager;

use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\AnotherDummyEntity;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;

/**
 * Class FixturesInteractionTest
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Manager
 * @author     Nicolas Guilloux <nicolas.guilloux@rich-id.fr>
 * @copyright  2014 - 2021 RichID (https://www.rich-id.fr)
 *
 * @covers \RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager
 * @TestConfig("fixtures")
 */
final class FixturesInteractionTest extends TestCase
{
    public function testEntityExistenceInDatabase(): void
    {
        $entity = $this->getRepository(DummyEntity::class)->find(1);
        self::assertInstanceOf(DummyEntity::class, $entity);
    }

    public function testUpdateEntity(): void
    {
        $entity = $this->getReference(DummyEntity::class, 'number-1');
        $entity->setIndex(999);

        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        $updatedEntity = $this->getRepository(DummyEntity::class)->findOneBy(['index' => 999]);
        self::assertSame($entity, $updatedEntity);
    }

    public function testCreateEntity(): void
    {
        $entity = new DummyEntity();
        $entity->setIndex(999);
        $entity->setAnyString('test');

        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        $updatedEntity = $this->getRepository(DummyEntity::class)->findOneBy(['index' => 999]);
        self::assertSame($entity, $updatedEntity);
    }

    public function testDeleteEntity(): void
    {
        $entity = $this->getReference(DummyEntity::class, 'number-1');
        $previousId = $entity->getId();
        self::assertInstanceOf(DummyEntity::class, $entity);
        $this->getManager()->remove($entity);
        $this->getManager()->flush();

        $updatedEntity = $this->getRepository(DummyEntity::class)->find($previousId);
        self::assertNull($entity->getId());
        self::assertNull($updatedEntity);
    }

    public function testCreateEntityWithLink(): void
    {
        $dummyEntity = $this->getReference(DummyEntity::class, 'number-50');
        $entity = new AnotherDummyEntity();
        $entity->setDummyEntity($dummyEntity);

        $this->getManager()->persist($entity);
        $this->getManager()->flush();

        self::assertNotNull($entity->getId());
    }
}
