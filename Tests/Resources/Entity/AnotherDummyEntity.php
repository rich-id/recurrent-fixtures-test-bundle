<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Another\DummyEntity
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2021 RichCongress (https://www.richcongress.com)
 *
 * @ORM\Entity
 * @ORM\Table(name="another_dummy_entity")
 */
class AnotherDummyEntity
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
     * @var DummyEntity
     *
     * @ORM\OneToOne(targetEntity="RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity")
     * @ORM\JoinColumn(name="dummy_entity_id", referencedColumnName="id", unique=true, onDelete="SET NULL")
     */
    private $dummyEntity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDummyEntity(): ?DummyEntity
    {
        return $this->dummyEntity;
    }

    public function setDummyEntity(DummyEntity $dummyEntity): self
    {
        $this->dummyEntity = $dummyEntity;

        return $this;
    }
}
