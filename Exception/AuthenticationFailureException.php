<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

final class AuthenticationFailureException extends AbstractException
{
    protected static $error = 'Failed to find an authenticator.';
}
