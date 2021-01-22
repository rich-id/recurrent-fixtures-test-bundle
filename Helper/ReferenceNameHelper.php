<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Helper;

/**
 * Class ReferenceNameHelper
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Helper
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class ReferenceNameHelper
{
    /**
     * Make constructor unavailable
     * @codeCoverageIgnore
     */
    private function __construct() {}

    /**
     * @param object|string $classOrObject
     */
    public static function transform($classOrObject, string $innerReference): string
    {
        $class = \is_object($classOrObject) ? \get_class($classOrObject) : $classOrObject;

        return $class . '_' . $innerReference;
    }

    /**
     * @return string[] [$class, $reference]
     */
    public static function reverse(string $innerReference): array
    {
        $exploded = explode('_', $innerReference);
        $class = array_shift($exploded);

        return [$class, implode('_', $exploded)];
    }
}
