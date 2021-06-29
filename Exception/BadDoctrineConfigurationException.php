<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

final class BadDoctrineConfigurationException extends AbstractException
{
    protected static $error = 'Doctrine is not configured well configured.';
}
