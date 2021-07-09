<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="dummy_user")
 */
class DummyUser implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return ['ROLE_DUMMY_USER'];
    }

    public function getPassword(): string
    {
        return 'password';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        // Nothing
    }

    public function getUsername(): string
    {
        return 'username';
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}
