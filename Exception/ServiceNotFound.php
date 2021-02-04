<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class ServiceNotFound
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class ServiceNotFound extends \LogicException
{
    public function __construct(string $service)
    {
        $message = sprintf('The service "%s" is missing from the container.', $service);

        parent::__construct($message);
    }
}
