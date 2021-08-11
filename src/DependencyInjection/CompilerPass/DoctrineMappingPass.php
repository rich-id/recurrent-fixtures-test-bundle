<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Definition;

final class DoctrineMappingPass extends AbstractCompilerPass
{
    public const PRIORITY = PHP_INT_MIN;

    public function process(ContainerBuilder $container): void
    {
        $container->setParameter('doctrine.empty_database_connection', 'empty_database');
        $container->setParameter('doctrine.empty_database_entity_manager', 'empty_database');

        $defaultMetadatDriverDefinition = $container->getDefinition('doctrine.orm.default_metadata_driver');
        $methodCalls = $defaultMetadatDriverDefinition->getMethodCalls();

        foreach ($methodCalls as $methodCall) {
            $methodName = $methodCall[0];
            $args = $methodCall[1];

            if ($methodName === 'addDriver') {
                $driver = \array_shift($args);
                $namespaces = $args;

                $driverDefinition = $this->resolveDefinition($driver, $container);
                $driverClass = $container->getParameterBag()->resolveString($driverDefinition->getClass());
                $driverArgs = $driverDefinition->getArguments();

                $this->addMapping($driverClass, $driverArgs, $namespaces, $container);
            }
        }
    }

    /** @param Reference|Definition $driver */
    protected function resolveDefinition($driver, ContainerBuilder $container): Definition
    {
        return $driver instanceof Reference
            ? $container->getDefinition((string) $driver)
            : $driver;
    }

    protected function addMapping(string $driverClass, array $driverArgs, array $namespaces, ContainerBuilder $container): void
    {
        $driver = new Definition($driverClass, $driverArgs);
        $pass = new DoctrineOrmMappingsPass($driver, $namespaces, ['doctrine.empty_database_entity_manager']);

        $pass->process($container);
    }
}
