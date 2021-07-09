<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\TestAuthentication;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationFailureException;
use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationTypeFailure;
use RichCongress\RecurrentFixturesTestBundle\Exception\ServiceNotFound;
use RichCongress\RecurrentFixturesTestBundle\Manager\FixtureManager;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator\MainSecurityAuthenticator;
use RichCongress\RecurrentFixturesTestBundle\TestCase\TestCase;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyUser;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Stubs\TokenStorage;
use RichCongress\TestFramework\TestConfiguration\Annotation\TestConfig;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @TestConfig("fixtures")
 *
 * @covers \RichCongress\RecurrentFixturesTestBundle\TestAuthentication\TestAuthenticationManager
 * @covers \RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator\MainSecurityAuthenticator
 */
final class TestAuthenticationTest extends TestCase
{
    public function testAuthenticateNotSupported(): void
    {
        $this->expectException(AuthenticationFailureException::class);
        $this->expectExceptionMessage('Failed to find an authenticator.');

        $this->authenticate(null, null);
    }

    public function testAuthenticateNoTokenStorage(): void
    {
        $authenticator = new MainSecurityAuthenticator(
            $this->getService(FixtureManager::class),
            $this->getService(SessionInterface::class)
        );

        $this->expectException(ServiceNotFound::class);
        $this->expectExceptionMessage('The service "security.token_storage" is missing from the container');

        $authenticator->authenticate($this->getClient(), DummyUser::class, 'user');
    }

    public function testAuthenticateNotAUser(): void
    {
        $this->expectException(AuthenticationTypeFailure::class);
        $this->expectExceptionMessage('Cannot authenticate using the class ');

        $authenticator = $this->getService(MainSecurityAuthenticator::class);
        $authenticator->authenticate($this->getClient(), DummyEntity::class, 'number-1');
    }

    public function testAuthenticate(): void
    {
        $this->authenticate(DummyUser::class, 'user');
        /** @var TokenStorage $tokenStorage */
        $tokenStorage = $this->getService('security.token_storage');

        /** @var UsernamePasswordToken $token */
        $token = $tokenStorage->getToken();
        self::assertInstanceOf(UsernamePasswordToken::class, $token);
        self::assertSame(['ROLE_DUMMY_USER'], $token->getRoleNames());
        self::assertInstanceOf(DummyUser::class, $token->getUser());

        $session = $this->getService(SessionInterface::class);
        self::assertNotNull($session->get('_security_main'));

        $cookieJar = $this->getClient()->getBrowser()->getCookieJar();
        $cookie = $cookieJar->get($session->getName());
        self::assertNotNull($cookie);
    }
}
