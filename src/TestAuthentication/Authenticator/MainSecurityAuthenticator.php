<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationBadRoleCountException;
use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationTypeFailure;
use RichCongress\RecurrentFixturesTestBundle\Exception\ServiceNotFound;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use RichCongress\WebTestBundle\TestCase\TestTrait\WithSessionTrait;
use RichCongress\WebTestBundle\WebTest\Client;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class MainSecurityAuthenticator implements TestAuthenticatorInterface
{
    use WithSessionTrait;

    /** @var string */
    protected static $sessionAttribute = '_security_main';

    /** @var FixtureManager */
    protected $fixturesManager;

    /** @var TokenStorageInterface|null */
    protected $tokenStorage;

    public function __construct(
        FixtureManager $fixtureManager,
        TokenStorageInterface $tokenStorage = null
    ) {
        $this->fixturesManager = $fixtureManager;
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

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        $this->withSession($client,
            fn ($session) => $session->set(static::$sessionAttribute, \serialize($token))
        );
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
