<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository\DummyEntityRepository;

/**
 * Class DummyEntity
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 */
#[ORM\Entity(repositoryClass: DummyEntityRepository::class)]
#[ORM\Table(name: 'dummy_entity')]
class DummyEntity
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'dummy_index', type: 'integer', nullable: true, options: ['unsigned' => true])]
    private ?int $index = 0;

    #[ORM\Column(name: 'dummy_reference', type: 'string', nullable: true)]
    private ?string $reference;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $anyString;

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function getIndex(): ?int
    {
        return $this->index;
    }

    public function setIndex(int $index): self
    {
        $this->index = $index;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAnyString(): string
    {
        return $this->anyString;
    }

    public function setAnyString(string $anyString): self
    {
        $this->anyString = $anyString;

        return $this;
    }

    public function __toString(): string
    {
        return self::class . '_' . $this->id;
    }
}
