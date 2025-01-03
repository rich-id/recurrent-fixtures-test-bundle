<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Manager;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use RichCongress\RecurrentFixturesTestBundle\DataFixture\DataFixtureInterface;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureClassNotFound;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureReferenceNotFound;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FixtureManager
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Manager
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
class FixtureManager
{
    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var SymfonyFixturesLoader */
    protected $fixturesLoader;

    /** @var DataFixtureInterface[] */
    protected $dataFixtures = [];

    /** @var ReferenceRepository|null */
    public static $referenceRepository;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->fixturesLoader = new SymfonyFixturesLoader($container);
    }

    public static function isInitialized(): bool
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
            $closestReference = $this->getClosestReference($class, $reference);

            throw $closestReference
                ? new FixtureReferenceNotFound($class, $reference, $closestReference)
                : new FixtureClassNotFound($class);
        }

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

    public function setDataFixtures(array $dataFixtures): void
    {
        foreach ($dataFixtures as $dataFixture) {
            $key = \get_class($dataFixture);
            $this->dataFixtures[$key] = $dataFixture;
        }
    }

    protected function checkInitialized(): void
    {
        if (!$this->isInitialized()) {
            throw new FixtureManagerNotInitialized();
        }
    }

    protected function initReferenceRepository(ReferenceRepository $referenceRepository): void
    {
        if (empty($this->dataFixtures)) {
            return;
        }

        foreach ($this->dataFixtures as $fixture) {
            $this->loadFixture($fixture);
        }

        $this->entityManager->flush();

        StaticDriver::forceCommit();
    }

    protected function getClosestReference(string $class, string $reference): ?string
    {
        $objectReferencesByClass = static::$referenceRepository->getReferencesByClass();
        $matchReference = null;
        $matchDistance = null;

        foreach ($objectReferencesByClass as $objectClass => $objectReferences) {
            foreach ($objectReferences as $objectReference => $object) {
                if ($objectClass !== $class) {
                    continue;
                }

                $distance = levenshtein($reference, (string) $objectReference);

                if ($matchDistance === null || $distance < $matchDistance) {
                    $matchDistance = $distance;
                    $matchReference = $objectReference;
                }
            }
        }

        return $matchReference;
    }

    protected function loadFixture(DataFixtureInterface $fixture): void
    {
        if ($this->fixturesLoader->hasFixture($fixture)) {
            return;
        }

        if ($fixture instanceof DependentFixtureInterface) {
            $dependencies = $fixture->getDependencies();

            foreach ($dependencies as $class) {
                $dependencyFixture = $this->dataFixtures[$class] ?? null;

                if ($dependencyFixture instanceof DataFixtureInterface) {
                    $this->loadFixture($dependencyFixture);
                }
            }
        }

        if ($fixture instanceof SharedFixtureInterface) {
            $fixture->setReferenceRepository(static::$referenceRepository);
        }

        $this->fixturesLoader->addFixture($fixture);
        $fixture->load($this->entityManager);
        $this->entityManager->flush();
    }
}
