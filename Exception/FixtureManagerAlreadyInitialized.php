<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class FixtureManagerAlreadyInitialized
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixtureManagerAlreadyInitialized extends \LogicException
{
    public function __construct()
    {
        parent::__construct('The FixtureManager has already been initialized. If you want to reset it, use the reset() method.');
    }
}
