<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DataFixturesPass extends AbstractCompilerPass
{
    public const DATA_FIXTURE_TAG = 'rich_congress_test.data_fixture';
    public const MANDATORY_SERVICES = [FixtureManager::class];

    public function process(ContainerBuilder $container): void
    {
        if (!self::checkMandatoryServices($container)) {
            return;
        }

        $taggedServices = $container->findTaggedServiceIds(self::DATA_FIXTURE_TAG);
        $references = self::arrayToReferences(array_keys($taggedServices));
        $definition = $container->findDefinition(FixtureManager::class);
        $definition->addMethodCall('setDataFixtures', [$references]);
    }

    /**
     * @param string[] $serviceNames
     *
     * @return Reference[]
     */
    protected static function arrayToReferences(array $serviceNames): array
    {
        return array_map(
            static function (string $serviceName) {
                return new Reference($serviceName);
            },
            $serviceNames
        );
    }
}
