<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Manager;

use Doctrine\Bundle\FixturesBundle\Loader\SymfonyFixturesLoader;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\Common\DataFixtures\SharedFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use RichCongress\RecurrentFixturesTestBundle\DataFixture\DataFixtureInterface;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureClassNotFound;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixtureReferenceNotFound;
use RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper;
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
        $this->dataFixtures = $dataFixtures;
    }

    protected function initReferenceRepository(ReferenceRepository $referenceRepository): void
    {
        if (!empty($this->dataFixtures)) {
            foreach ($this->dataFixtures as $dataFixture) {
                $this->fixturesLoader->addFixture($dataFixture);
            }

            /** @var DataFixtureInterface $fixture */
            foreach ($this->fixturesLoader->getFixtures() as $fixture) {
                $this->loadFixture($fixture);
            }
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
        if ($fixture instanceof SharedFixtureInterface) {
            $fixture->setReferenceRepository(static::$referenceRepository);
        }

        $fixture->load($this->entityManager);
        $this->entityManager->clear();
    }
}
