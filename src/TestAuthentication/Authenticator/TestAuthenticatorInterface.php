<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\TestAuthentication\Authenticator;

use RichCongress\WebTestBundle\WebTest\Client;
use Symfony\Component\Security\Core\User\UserInterface;

interface TestAuthenticatorInterface
{
    public function authenticate(Client $client, ?string $class, ?string $reference): void;
    public function authenticateUser(Client $client, UserInterface $user): void;
    public function deauthenticate(Client $client, ?string $class, ?string $reference): void;
    public function supports(?string $class, ?string $reference): bool;
    public function supportsUser(UserInterface $user): bool;
    public function getPriority(): int;
}
