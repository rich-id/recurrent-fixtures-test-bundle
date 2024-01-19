<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationBadRoleCountException;
use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationTypeFailure;
use RichCongress\RecurrentFixturesTestBundle\Exception\ServiceNotFound;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use RichCongress\WebTestBundle\WebTest\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class MainSecurityAuthenticator implements TestAuthenticatorInterface
{
    /** @var string */
    protected static $sessionAttribute = '_security_main';

    /** @var FixtureManager */
    protected $fixturesManager;

    /** @var SessionInterface */
    protected $session;

    /** @var TokenStorageInterface|null */
    protected $tokenStorage;

    public function __construct(
        FixtureManager $fixtureManager,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage = null
    ) {
        $this->fixturesManager = $fixtureManager;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    public function authenticate(Client $client, ?string $class, ?string $reference): void
    {
        if ($this->tokenStorage === null) {
            throw new ServiceNotFound('security.token_storage');
        }

        $user = $this->fixturesManager->getReference($class, $reference);

        if (!$user instanceof UserInterface) {
            throw new AuthenticationTypeFailure(\get_class($user));
        }

        $this->authenticateUser($client, $user);
    }

    public function authenticateUser(Client $client, UserInterface $user): void
    {
        if (count($user->getRoles()) <= 0) {
            AuthenticationBadRoleCountException::throw();
        }

        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        $this->session->set(static::$sessionAttribute, \serialize($token));
        $this->session->save();

        $cookie = new Cookie($this->session->getName(), $this->session->getId());
        $client->getBrowser()->getCookieJar()->set($cookie);
    }

    public function deauthenticate(Client $client, ?string $class, ?string $reference): void
    {
        $this->tokenStorage->setToken();
    }

    public function supports(?string $class, ?string $reference): bool
    {
        return is_subclass_of($class, UserInterface::class) || $class === UserInterface::class;
    }

    public function supportsUser(UserInterface $user): bool
    {
        return true;
    }

    public function getPriority(): int
    {
        return -100;
    }
}
