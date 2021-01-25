<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestCase;

use RichCongress\WebTestBundle\TestCase\TestTrait\ControllerTestUtilitiesTrait;
use RichCongress\WebTestBundle\TestCase\TestTrait\WebTestAssertionsTrait;

/**
 * Class ControllerTestCase
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\TestCase
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @TestConfig("fixtures")
 */
abstract class ControllerTestCase extends TestCase
{
    use ControllerTestUtilitiesTrait;
    use WebTestAssertionsTrait;
}
