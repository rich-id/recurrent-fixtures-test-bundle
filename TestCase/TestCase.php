<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestCase;

use RichCongress\RecurrentFixturesTestBundle\TestCase\Internal\FixtureTestCase;
use RichCongress\RecurrentFixturesTestBundle\TestTrait\AuthenticationTrait;
use RichCongress\WebTestBundle\TestCase\Internal\WebTestCase;

/**
 * Class TestCase
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
abstract class TestCase extends \RichCongress\WebTestBundle\TestCase\TestCase
{
    use AuthenticationTrait;

    /** @var FixtureTestCase */
    private $fixtureTestCase;

    public function setUpTestCase(): void
    {
        parent::setUpTestCase();

        $container = WebTestCase::isEnabled() ? $this->getContainer() : null;
        $this->fixtureTestCase = new FixtureTestCase($container);
        $this->fixtureTestCase->setUp();
    }

    public function tearDown(): void
    {
        $this->fixtureTestCase->tearDown();

        parent::tearDownTestCase();
    }

    public function getReference(string $class, string $reference)
    {
        return $this->fixtureTestCase->getReference($class, $reference);
    }
}
