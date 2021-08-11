<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestTrait;

use App\Entity\User;
use RichCongress\RecurrentFixturesTestBundle\Exception\RoleNotFound;

/**
 * Trait RolesProviderTrait
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\TestTrait
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * The rolesProvider methods allows to test mapped roles.
 */
trait RolesProviderTrait
{
    /**
     * @return array List of the roles to map to a reference
     *
     * [
     *     'NotLogged' => ['class' => null, 'reference' => null],
     *     'AnyRole'   => ['class' => UserInterface::class, 'reference' => 'any_reference'],
     *     'Admin'     => ['class' => UserInterface::class, 'reference' => 'admin'],
     * ]
     */
    protected static function getRolesMapping(): array
    {
        return [];
    }

    /**
     * @param array $extraExpectations [$userClass, $userReference, ...$extraParams]
     */
    protected function rolesProvider(array $expectations, array $extraExpectations = []): array
    {
        $rolesMapping = static::getRolesMapping();
        $fullExpectations = [];

        foreach ($expectations as $role => $content) {
            if (!array_key_exists($role, $rolesMapping)) {
                throw new RoleNotFound($role, $rolesMapping);
            }

            $referenceInfo = $rolesMapping[$role] ?? [];
            $orderedReferenceInfo = [
                $referenceInfo['class'] ?? null,
                $referenceInfo['reference'] ?? null,
            ];

            $fullExpectations[] = array_merge($orderedReferenceInfo, (array) $content);
        }

        return array_merge($fullExpectations, $extraExpectations);
    }
}
