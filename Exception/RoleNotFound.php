<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class RoleNotFound
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class RoleNotFound extends \LogicException
{
    public function __construct(string $role, array $rolesMapping)
    {
        $message = sprintf(
            'The role "%s" is missing from the roles mapping. Only the following roles are available: %s.',
            $role,
            array_keys($rolesMapping)
        );

        parent::__construct($message);
    }
}
