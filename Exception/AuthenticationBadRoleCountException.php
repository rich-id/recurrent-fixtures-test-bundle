<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

final class AuthenticationBadRoleCountException extends AbstractException
{
    protected static $error = 'The user must at least have 1 role.';
}
