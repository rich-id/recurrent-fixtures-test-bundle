<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DataFixture;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use RichCongress\RecurrentFixturesTestBundle\Helper\ReferenceNameHelper;
use RichCongress\TestTools\Helper\ForceExecutionHelper;
use RichCongress\WebTestBundle\Doctrine\Driver\StaticDriver;

/**
 * Class AbstractFixture
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\DataFixture
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
abstract class AbstractFixture extends Fixture implements DataFixtureInterface
{
    /** @var ObjectManager */
    protected $manager;

    /**
     * Loads the fixtures. All persisted data will be flushed at the end of the function.
     *
     * The recommanded way to create an object is to use the following function :
     *  $this->createobject('reference-1', Object::class, ['property' => 'value'])
     */
    abstract protected function loadFixtures(): void;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadFixtures();

        StaticDriver::withoutTransaction(static function () use ($manager) {
           $manager->flush();
        });
    }

    /**
     * @param string $name
     * @param object $object
     */
    public function setReference($name, $object): void
    {
        $reference = ReferenceNameHelper::transform($object, $name);

        parent::setReference($reference, $object);
    }

    /**
     * @param object|mixed    $object
     * @param string|string[] $references
     */
    protected function save($object, $references): void
    {
        foreach ((array) $references as $reference) {
            $this->setReference($reference, $object);
        }

        if ($this->manager !== null) {
            $this->manager->persist($object);
        }
    }

    /**
     * @param string|string[] $references
     *
     * @return object
     */
    protected function createObject(string $class, $references, array $data)
    {
        $object = new $class();

        foreach ($data as $property => $value) {
            ForceExecutionHelper::setValue($object, $property, $value);
        }

        $this->save($object, $references);

        return $object;
    }
}
