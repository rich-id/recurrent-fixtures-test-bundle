<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Manager;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerAlreadyInitialized;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureManagerNotInitialized;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\WebTestBundle\Doctrine\DatabaseSchemaInitializer;

/**
 * Class AbstractORMFixtureManager
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Manager
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractORMFixtureManager implements FixtureManagerInterface
{
    /** @var ReferenceRepository|null */
    public static $referenceRepository;

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function isInitialized(): bool
    {
        return static::$referenceRepository !== null;
    }

    public function init(): void
    {
        if ($this->isInitialized()) {
            throw new FixtureManagerAlreadyInitialized();
        }

        static::$referenceRepository = new ReferenceRepository($this->entityManager);
        $this->initReferenceRepository(static::$referenceRepository);
    }

    public function reset(): void
    {
        static::$referenceRepository = null;
    }

    public function hasReference(string $class, string $reference): bool
    {
        $this->checkInitialized();

        return static::$referenceRepository->hasReference($reference, $class);
    }

    public function getReference(string $class, string $reference)
    {
        if (!$this->hasReference($class, $reference)) {
            return null;
        }
        
        $object = static::$referenceRepository->getReference($reference, $class);
        $identity = static::$referenceRepository->getIdentitiesByClass()[$class][$reference] ?? null;
        $metadata = $this->entityManager->getClassMetadata($class);

        if (!$this->entityManager->contains($object)) {
            if ($identity !== null ) {
                $object = $this->entityManager->getReference($metadata->name, $identity);
                static::$referenceRepository->setReference($reference, $object);
            } else {
                $id = $object->getId();
                $object = $id !== null ? $this->entityManager->find($class, $id) : null;
            }
        }

        return $object;
    }

    abstract protected function initReferenceRepository(ReferenceRepository $referenceRepository): void;

    protected function checkInitialized(): void
    {
        if (!$this->isInitialized()) {
            throw new FixtureManagerNotInitialized();
        }
    }
}
