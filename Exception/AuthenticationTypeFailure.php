<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationTypeFailure
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class AuthenticationTypeFailure extends \LogicException
{
    public function __construct(string $class)
    {
        $message = sprintf(
            'Cannot authenticate using the class "%s", the class must implement the interface "%s"'
            , $class
            , UserInterface::class
        );

        parent::__construct($message);
    }
}
