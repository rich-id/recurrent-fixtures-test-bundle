<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class DummyEntity
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @ORM\Entity(repositoryClass="RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository\DummyEntityRepository")
 * @ORM\Table(name="dummy_entity")
 */
class DummyEntity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", name="id", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", name="dummy_index", nullable=true, options={"unsigned":true})
     */
    private $index = 0;

    public function getId(): ?int
    {
        return $this->id;
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
}
