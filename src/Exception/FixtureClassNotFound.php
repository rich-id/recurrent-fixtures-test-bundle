<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class FixtureClassNotFound
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixtureClassNotFound extends \LogicException
{
    public function __construct(string $class)
    {
        $message = sprintf('No fixture found for the class "%s".', $class);
        parent::__construct($message);
    }
}
