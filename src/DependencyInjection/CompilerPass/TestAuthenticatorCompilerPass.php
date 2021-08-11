<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass;

use RichCongress\BundleToolbox\Configuration\AbstractCompilerPass;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\TestAuthenticationManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TestAuthenticatorCompilerPass extends AbstractCompilerPass
{
    public const AUTHENTICATOR_SERVICE_TAG = 'rich_congress.test_authenticator';

    public function process(ContainerBuilder $container): void
    {
        $authenticators = $container->findTaggedServiceIds(self::AUTHENTICATOR_SERVICE_TAG);
        $references = array_map(
            static function (string $serviceId): Reference {
                return new Reference($serviceId);
            },
            array_keys($authenticators)
        );

        $definition = $container->findDefinition(TestAuthenticationManager::class);
        $definition->addMethodCall('setAuthenticators', [$references]);
    }
}
