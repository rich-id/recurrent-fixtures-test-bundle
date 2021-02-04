<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestTrait;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationTypeFailure;
use RichCongress\RecurrentFixturesTestBundle\Exception\ServiceNotFound;
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
    protected function authenticate(?string $class, ?string $reference, string $sessionAttribute = '_security_main'): void
    {
        if ($class === null || $reference === null) {
            return;
        }

        $user = $this->getReference($class, $reference);

        if (!$user instanceof UserInterface) {
            throw new AuthenticationTypeFailure(\get_class($user));
        }

        $this->authenticateUser($user, $sessionAttribute);
    }

    protected function authenticateUser(UserInterface $user, string $sessionAttribute = '_security_main'): void
    {
        /** @var ContainerInterface $container */
        $container = $this->getContainer();
        $tokenStorage = $this->getSecurityTokenStorage();

        if ($tokenStorage === null) {
            throw new ServiceNotFound('security.token_storage');
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $tokenStorage->setToken($token);

        /** @var SessionInterface $session */
        $session = $container->get('session');
        $session->set($sessionAttribute, \serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        static::getClient()->getBrowser()->getCookieJar()->set($cookie);
    }

    /**
     * @internal
     */
    protected function authenticationTearDown(): void
    {
        $tokenStorage = $this->getSecurityTokenStorage();

        if ($tokenStorage !== null) {
            $tokenStorage->setToken();
        }

        parent::tearDown();
    }

    /**
     * @return TokenStorageInterface|null
     */
    private function getSecurityTokenStorage(): ?TokenStorageInterface
    {
        $tokenStorage = $this->getContainer()->has('security.token_storage')
            ? $this->getContainer()->get('security.token_storage')
            : null;

        return $tokenStorage instanceof TokenStorageInterface ? $tokenStorage : null;
    }
}
