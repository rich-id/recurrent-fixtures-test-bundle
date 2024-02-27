<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\Cache;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataFactory;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Proxy\ProxyFactory;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\Query\FilterCollection;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use RichCongress\RecurrentFixturesTestBundle\Exception\BadDoctrineConfigurationException;
use RichCongress\RecurrentFixturesTestBundle\TestCase\Internal\FixtureTestCase;

final class EntityManagerWrapper implements EntityManagerInterface
{
    /** @var EntityManagerInterface */
    private $defaultEntityManager;

    /** @var EntityManagerInterface */
    private $emptyEntityManager;

    public function __construct(EntityManagerInterface $defaultEntityManager, ManagerRegistry $managerRegistry)
    {
        if (!array_key_exists('empty_database', $managerRegistry->getManagerNames())) {
            BadDoctrineConfigurationException::throw();
        }

        $this->defaultEntityManager = $defaultEntityManager;
        $this->emptyEntityManager = $managerRegistry->getManager('empty_database');
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return FixtureTestCase::isEnabled() ? $this->defaultEntityManager : $this->emptyEntityManager;
    }

    /** {@inheritdoc} */
    public function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }

    /** {@inheritdoc} */
    public function getExpressionBuilder(): Expr
    {
        return $this->getEntityManager()->getExpressionBuilder();
    }

    /** {@inheritdoc} */
    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    /** {@inheritdoc} */
    public function transactional($func)
    {
        return $this->getEntityManager()->transactional($func);
    }

    /** {@inheritdoc} */
    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    /** {@inheritdoc} */
    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    /** {@inheritdoc} */
    public function createQuery($dql = ''): Query
    {
        return $this->getEntityManager()->createQuery($dql);
    }

    /** {@inheritdoc} */
    public function createNamedQuery($name)
    {
        return $this->getEntityManager()->createNamedQuery($name);
    }

    /** {@inheritdoc} */
    public function createNativeQuery($sql, ResultSetMapping $rsm): NativeQuery
    {
        return $this->getEntityManager()->createNativeQuery($sql, $rsm);
    }

    /** {@inheritdoc} */
    public function createNamedNativeQuery($name)
    {
        return $this->getEntityManager()->createNamedNativeQuery($name);
    }

    /** {@inheritdoc} */
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    /** {@inheritdoc} */
    public function getReference($entityName, $id): ?object
    {
        return $this->getEntityManager()->getReference($entityName, $id);
    }

    /** {@inheritdoc} */
    public function getPartialReference($entityName, $identifier)
    {
        return $this->getEntityManager()->getPartialReference($entityName, $identifier);
    }

    /** {@inheritdoc} */
    public function close(): void
    {
        $this->getEntityManager()->close();
    }

    /** {@inheritdoc} */
    public function copy($entity, $deep = false)
    {
        return $this->getEntityManager()->copy($entity, $deep);
    }

    /** {@inheritdoc} */
    public function lock($entity, $lockMode, $lockVersion = null): void
    {
        $this->getEntityManager()->lock($entity, $lockMode, $lockVersion);
    }

    /** {@inheritdoc} */
    public function find($className, $id, $lockMode = null, $lockVersion = null): ?object
    {
        return $this->getEntityManager()->find($className, $id, $lockMode, $lockVersion);
    }

    /** {@inheritdoc} */
    public function flush($entity = null): void
    {
        $this->getEntityManager()->flush($entity);
    }

    /** {@inheritdoc} */
    public function getEventManager(): EventManager
    {
        return $this->getEntityManager()->getEventManager();
    }

    /** {@inheritdoc} */
    public function getConfiguration(): Configuration
    {
        return $this->getEntityManager()->getConfiguration();
    }

    /** {@inheritdoc} */
    public function isOpen(): bool
    {
        return $this->getEntityManager()->isOpen();
    }

    /** {@inheritdoc} */
    public function getUnitOfWork(): UnitOfWork
    {
        return $this->getEntityManager()->getUnitOfWork();
    }

    /** {@inheritdoc} */
    public function getHydrator($hydrationMode)
    {
        return $this->getEntityManager()->getHydrator($hydrationMode);
    }

    /** {@inheritdoc} */
    public function newHydrator($hydrationMode): AbstractHydrator
    {
        return $this->getEntityManager()->newHydrator($hydrationMode);
    }

    /** {@inheritdoc} */
    public function getProxyFactory(): ProxyFactory
    {
        return $this->getEntityManager()->getProxyFactory();
    }

    /** {@inheritdoc} */
    public function getFilters(): FilterCollection
    {
        return $this->getEntityManager()->getFilters();
    }

    /** {@inheritdoc} */
    public function isFiltersStateClean(): bool
    {
        return $this->getEntityManager()->isFiltersStateClean();
    }

    /** {@inheritdoc} */
    public function hasFilters(): bool
    {
        return $this->getEntityManager()->hasFilters();
    }

    /** {@inheritdoc} */
    public function getCache(): ?Cache
    {
        return $this->getEntityManager()->getCache();
    }

    /** {@inheritdoc} */
    public function getRepository($className): EntityRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    /** {@inheritdoc} */
    public function getClassMetadata($className): ClassMetadata
    {
        return $this->getEntityManager()->getClassMetadata($className);
    }

    /** {@inheritdoc} */
    public function persist($object): void
    {
        $this->getEntityManager()->persist($object);
    }

    /** {@inheritdoc} */
    public function remove($object): void
    {
        $this->getEntityManager()->remove($object);
    }

    /** {@inheritdoc} */
    public function merge($object)
    {
        return $this->getEntityManager()->merge($object);
    }

    /** {@inheritdoc} */
    public function clear($objectName = null)
    {
        return $this->getEntityManager()->clear($objectName);
    }

    /** {@inheritdoc} */
    public function detach($object)
    {
        return $this->getEntityManager()->detach($object);
    }

    /** {@inheritdoc} */
    public function refresh($object, LockMode|int|null $lockMode = null): void
    {
        $this->getEntityManager()->refresh($object, $lockMode);
    }

    /** {@inheritdoc} */
    public function initializeObject($obj)
    {
        return $this->getEntityManager()->initializeObject($obj);
    }

    /** {@inheritdoc} */
    public function contains($object)
    {
        return $this->getEntityManager()->contains($object);
    }

    /** {@inheritdoc} */
    public function getMetadataFactory(): ClassMetadataFactory
    {
        return $this->getEntityManager()->getMetadataFactory();
    }

    public function wrapInTransaction(callable $func): mixed
    {
        return $this->getEntityManager()->wrapInTransaction($func);
    }
}
