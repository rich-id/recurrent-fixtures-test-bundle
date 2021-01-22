<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestCase\Internal;

use Psr\Container\ContainerInterface;
use RichCongress\RecurrentFixturesTestBundle\Exception\FixturesNotInitialized;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManagerInterface;
use RichCongress\TestFramework\TestConfiguration\TestConfiguration;

/**
 * Class FixtureTestCase
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\TestCase\Internal
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixtureTestCase
{
    public const CONFIG_ENABLE_FLAGS = ['fixtures'];

    /** @var ContainerInterface|null */
    private $container;

    public function __construct(?ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setUp(): void
    {
        if (!self::isEnabled()) {
            return;
        }

        $fixtureManager = $this->getFixtureManager();

        if (!$fixtureManager->isInitialized()) {
            $fixtureManager->init();
        }
    }

    public function tearDown(): void
    {
        // Empty
    }

    /**
     * @return mixed
     */
    public function getReference(string $class, string $reference)
    {
        return $this->getFixtureManager()->getReference($class, $reference);
    }

    public static function isEnabled(): bool
    {
        foreach (self::CONFIG_ENABLE_FLAGS as $flag) {
            if (TestConfiguration::get($flag) === true) {
                return true;
            }
        }

        return false;
    }

    private function getFixtureManager(): FixtureManagerInterface
    {
        if (!self::isEnabled()) {
            throw new FixturesNotInitialized();
        }

        return $this->container->get(FixtureManagerInterface::class);
    }
}
