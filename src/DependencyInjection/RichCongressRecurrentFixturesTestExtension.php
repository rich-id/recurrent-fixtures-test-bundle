<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\DependencyInjection;

use RichCongress\BundleToolbox\Configuration\AbstractExtension;
use RichCongress\RecurrentFixturesTestBundle\DataFixture\DataFixtureInterface;
use RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass\DataFixturesPass;
use RichCongress\RecurrentFixturesTestBundle\DependencyInjection\CompilerPass\TestAuthenticatorCompilerPass;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator\TestAuthenticatorInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RichCongressRecurrentFixturesTestExtension extends AbstractExtension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this->parseConfiguration(
            $container,
            new Configuration(),
            $configs
        );

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources'));
        $loader->load('services.xml');

        $container
            ->registerForAutoconfiguration(DataFixtureInterface::class)
            ->addTag(DataFixturesPass::DATA_FIXTURE_TAG);

        $container
            ->registerForAutoconfiguration(TestAuthenticatorInterface::class)
            ->addTag(TestAuthenticatorCompilerPass::AUTHENTICATOR_SERVICE_TAG);
    }
}
