<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DataFixture;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper;

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
        $innerReference = ReferenceNameHelper::transform($class, $reference);

        return $this->referenceRepository->getReference($innerReference);
    }

    /**
     * @param object $object
     */
    public function setReference(string $reference, $object): void
    {
        $innerReference = ReferenceNameHelper::transform($object, $reference);
        $this->referenceRepository->setReference($innerReference, $object);
    }

    public function addReference(string $reference, $object): void
    {
        $innerReference = ReferenceNameHelper::transform($object, $reference);
        $this->referenceRepository->addReference($innerReference, $object);
    }

    public function hasReference(string $class, string $reference): bool
    {
        $innerReference = ReferenceNameHelper::transform($class, $reference);

        return $this->referenceRepository->hasReference($innerReference);
    }
}
