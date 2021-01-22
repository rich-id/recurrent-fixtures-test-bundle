<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Exception;

/**
 * Class FixtureReferenceNotFound
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Exception
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
final class FixtureReferenceNotFound extends \LogicException
{
    public function __construct(string $class, string $reference, string $closestReference)
    {
        $message = sprintf(
            'The reference "%s" for the class "%s" does not exist. Did you mean "%s"?',
            $reference,
            $class,
            $closestReference
        );

        parent::__construct($message);
    }
}
