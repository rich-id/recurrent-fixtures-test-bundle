<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Doctrine\ORM\Configuration;
use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use RichCongress\WorkspaceBundle\Helpers\Str;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

final class DoctrineMappingPass extends AbstractCompilerPass
{
    public const PRIORITY = PHP_INT_MIN;

    public function process(ContainerBuilder $container): void
    {
        $defaultMetadatDriverDefinition = $container->getDefinition('doctrine.orm.default_metadata_driver');
        $methodCalls = $defaultMetadatDriverDefinition->getMethodCalls();

        foreach ($methodCalls as $methodCall) {
            $methodName = $methodCall[0];
            $args = $methodCall[1];

            if ($methodName === 'addDriver') {
                $driver = \array_shift($args);
                $namespaces = $args;

                $driverDefinition = $this->resolveDefinition($driver, $container);
                $driverClass = $this->resolveClass($driverDefinition, $container);
                $driverArgs = $this->resolveArguments($driverDefinition);

                $this->addMapping($driverClass, $driverArgs, $namespaces, $container);
            }
        }
    }

    /** @param Reference|Definition $driver */
    protected function resolveDefinition(mixed $driver, ContainerBuilder $container): Definition
    {
        return $driver instanceof Reference
            ? $container->getDefinition((string) $driver)
            : $driver;
    }

    protected function resolveClass(Definition $driverDefinition, ContainerBuilder $container): string
    {
        $driverClass = $driverDefinition->getClass();

        return $driverClass[0] === '%' && $driverClass[strlen($driverClass) - 1] === '%'
            ? $container->getParameter(\trim($driverClass, '%'))
            : $driverClass;
    }

    protected function resolveArguments(Definition $driverDefinition): array
    {
        return \array_filter($driverDefinition->getArguments(), static fn($arg) => \is_array($arg));
    }

    protected function addMapping(string $driverClass, array $driverArgs, array $namespaces, ContainerBuilder $container): void
    {
        $driver = new Definition($driverClass, $driverArgs);
        $pass = new DoctrineOrmMappingsPass($driver, $namespaces, ['doctrine.empty_database_entity_manager']);

        $pass->process($container);
    }
}
