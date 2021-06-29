<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

abstract class AbstractException extends \Exception
{
    protected static $error;

    public function __construct()
    {
        $classParts = explode('\\', static::class);
        $documentation = sprintf(
            'You can check the following link to learn more: https://github.com/rich-id/recurrent-fixtures-test-bundle/blob/master/Docs/Exceptions.md#%s',
            array_pop($classParts)
        );

        parent::__construct(static::$error . "\n" . $documentation);
    }

    public static function throw(): void
    {
        throw new static();
    }
}
