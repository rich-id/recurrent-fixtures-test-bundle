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
use RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class FixtureManager
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Manager
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
class FixtureManager extends AbstractORMFixtureManager
{
    /** @var DataFixtureInterface[] */
    protected $dataFixtures = [];

    /** @var SymfonyFixturesLoader */
    protected $fixturesLoader;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        parent::__construct($entityManager);

        $this->fixturesLoader = new SymfonyFixturesLoader($container);
    }

    public function setDataFixtures(array $dataFixtures): void
    {
        foreach ($dataFixtures as $dataFixture) {
            $key = \get_class($dataFixture);
            $this->dataFixtures[$key] = $dataFixture;
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

        if (StaticDriver::isInTransaction()) {
            StaticDriver::commit();
            StaticDriver::beginTransaction();
        }
    }

    public function getReference(string $class, string $reference)
    {
        if (!$this->hasReference($class, $reference)) {
            $closestReference = $this->getClosestReference($class, $reference);

            throw $closestReference
                ? new FixtureReferenceNotFound($class, $reference, $closestReference)
                : new FixtureClassNotFound($class);
        }

        return parent::getReference($class, $reference);
    }

    protected function getClosestReference(string $class, string $reference): ?string
    {
        $objectReferences = array_keys(static::$referenceRepository->getReferences());
        $matchReference = null;
        $matchDistance = null;

        foreach ($objectReferences as $objectReference) {
            [$objectClass, $objectInnerReference] = ReferenceNameHelper::reverse($objectReference);

            if ($objectClass !== $class) {
                continue;
            }

            $distance = levenshtein($reference, $objectInnerReference);

            if ($matchDistance === null || $distance < $matchDistance) {
                $matchDistance = $distance;
                $matchReference = $objectInnerReference;
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
