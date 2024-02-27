<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DataFixture;

use Doctrine\Common\DataFixtures\ReferenceRepository;

/**
 * Trait ReferenceRepositoryTrait
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\DataFixture
 * @author     Nicolas Guilloux <nicolas.guilloux@rich-id.fr>
 * @copyright  2014 - 2021 RichID (https://www.rich-id.fr)
 */
trait ReferenceRepositoryTrait
{
    /** @var ReferenceRepository */
    protected $referenceRepository;

    public function setReferenceRepository(ReferenceRepository $referenceRepository): void
    {
        $this->referenceRepository = $referenceRepository;
    }

    public function getReference(string $class, string $reference)
    {
        return $this->referenceRepository->getReference($reference, $class);
    }

    /**
     * @param object $object
     */
    public function setReference(string $reference, $object): void
    {
        $this->referenceRepository->setReference($reference, $object);
    }

    public function addReference(string $reference, $object): void
    {
        $this->referenceRepository->addReference($reference, $object);
    }

    public function hasReference(string $class, string $reference): bool
    {
        return $this->referenceRepository->hasReference($reference, $class);
    }
}
