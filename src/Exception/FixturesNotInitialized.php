<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class FixturesNotInitialized
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixturesNotInitialized extends \LogicException
{
    /** @var string  */
    protected static $error = 'The fixtures are not initialized. Did you add the annotation `@TestConfig("fixtures")` to your method or class?';

    /** @var string  */
    protected static $documentation = 'https://github.com/richcongress/recurrent-fixtures-test-bundle/blob/master/Docs/Exceptions.md#FixturesNotInitializedException';

    /**
     * KernelNotInitializedException constructor.
     */
    public function __construct()
    {
        $message = self::$error;
        $message .= "\nCheck the documentation: " . self::$documentation;

        parent::__construct($message);
    }
}
