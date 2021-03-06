<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Entity\DummyEntity;

/**
 * Class DummyEntityRepository
 *
 * @package    RichCongress\RecurrentFixturesTestBundle\Tests\Resources\Repository
 * @author     Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright  2014 - 2020 RichCongress (https://www.richcongress.com)
 */
final class DummyEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyEntity::class);
    }
}
