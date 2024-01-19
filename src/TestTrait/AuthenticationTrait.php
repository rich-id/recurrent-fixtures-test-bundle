<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestTrait;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationTypeFailure;
use RichCongress\RecurrentFixturesTestBundle\Exception\ServiceNotFound;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator\TestAuthenticatorInterface;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\TestAuthenticationManager;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AuthenticationTrait
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\TestTrait
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
trait AuthenticationTrait
{
    protected function authenticate(?string $class, ?string $reference): void
    {
        $client = $this->getClient();
        $this->getTestAuthenticationManager()->authenticate($client, $class, $reference);
    }

    protected function authenticateUser(UserInterface $user): void
    {
        $client = $this->getClient();
        $this->getTestAuthenticationManager()->authenticateUser($client, $user);
    }

    /**
     * @internal
     */
    protected function authenticationTearDown(): void
    {
        $client = $this->getClient();
        $this->getTestAuthenticationManager()->deauthenticate($client);

        parent::tearDown();
    }

    private function getTestAuthenticationManager(): TestAuthenticationManager
    {
        $testAuthenticationManager = $this->getContainer()->get(TestAuthenticationManager::class);

        if (!$testAuthenticationManager instanceof TestAuthenticationManager) {
            throw new \LogicException('Fail to get TestAuthenticationManager');
        }

        return $testAuthenticationManager;
    }
}
