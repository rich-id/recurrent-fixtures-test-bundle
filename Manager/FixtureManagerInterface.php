<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Manager;

/**
 * Class FixtureManagerInterface
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Manager
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
interface FixtureManagerInterface
{
    public function isInitialized(): bool;
    public function init(): void;
    public function reset(): void;

    public function hasReference(string $class, string $reference): bool;
    public function getReference(string $class, string $reference);
}
