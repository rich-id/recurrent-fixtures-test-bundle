<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class FixtureManagerNotInitialized
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixtureManagerNotInitialized extends \LogicException
{
    public function __construct()
    {
        parent::__construct('The FixtureManager has not been initialized. Execute init() before doing any action');
    }
}
