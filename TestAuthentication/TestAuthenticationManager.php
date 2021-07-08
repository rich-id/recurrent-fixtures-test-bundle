<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestAuthentication;

use RichCongress\RecurrentFixturesTestBundle\Exception\AuthenticationFailureException;
use RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator\TestAuthenticatorInterface;
use RichCongress\WebTestBundle\WebTest\Client;

class TestAuthenticationManager
{
    /** @var TestAuthenticatorInterface[] */
    protected $authenticators = [];

    /** @var array<int, mixed>|null */
    protected $lastAuthentication;

    /**
     * @param TestAuthenticatorInterface[] $authenticators
     */
    public function setAuthenticators(array $authenticators): void
    {
        usort(
            $authenticators,
            static function (TestAuthenticatorInterface $left, TestAuthenticatorInterface $right): int {
                if ($left->getPriority() === $right->getPriority()) {
                    return 0;
                }

                return $left->getPriority() > $right->getPriority() ? 1 : -1;
            }
        );

        $this->authenticators = $authenticators;
    }

    public function authenticate(Client $client, ?string $class, ?string $reference): void
    {
        foreach ($this->authenticators as $authenticator) {
            if ($authenticator->supports($class, $reference)) {
                $authenticator->authenticate($client, $class, $reference);

                $this->lastAuthentication = [
                    'class'         => $class,
                    'reference'     => $reference,
                    'authenticator' => $authenticator,
                ];

                return;
            }
        }

        AuthenticationFailureException::throw();
    }

    public function deauthenticate(Client $client): void
    {
        /** @var TestAuthenticatorInterface|null $authenticator */
        $authenticator = $this->lastAuthentication['authenticator'] ?? null;
        $class = $this->lastAuthentication['class'] ?? null;
        $reference = $this->lastAuthentication['reference'] ?? null;
        $this->lastAuthentication = null;

        if ($authenticator !== null) {
            $authenticator->deauthenticate($client, $class, $reference);
        }
    }
}
