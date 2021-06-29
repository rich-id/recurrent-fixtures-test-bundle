<?php declare(strict_types=1);

namespace RichCongress\RecurrentFixturesTestBundle\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use RichCongress\RecurrentFixturesTestBundle\Exception\BadDoctrineConfigurationException;
use RichCongress\RecurrentFixturesTestBundle\TestCase\Internal\FixtureTestCase;

final class EntityManagerWrapper implements EntityManagerInterface
{
    /** @var EntityManagerInterface */
    private $fixturesEntityManager;

    /** @var EntityManagerInterface */
    private $emptyEntityManager;

    public function __construct(EntityManagerInterface $fixturesEntityManager, EntityManagerInterface $emptyEntityManager = null)
    {
        if ($emptyEntityManager === null) {
            BadDoctrineConfigurationException::throw();
        }

        $this->fixturesEntityManager = $fixturesEntityManager;
        $this->emptyEntityManager = $emptyEntityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return FixtureTestCase::isEnabled() ? $this->fixturesEntityManager : $this->emptyEntityManager;
    }

    /** {@inheritdoc} */
    public function getConnection()
    {
        return $this->getEntityManager()->getConnection();
    }

    /** {@inheritdoc} */
    public function getExpressionBuilder()
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
    public function createQuery($dql = '')
    {
        return $this->getEntityManager()->createQuery($dql);
    }

    /** {@inheritdoc} */
    public function createNamedQuery($name)
    {
        return $this->getEntityManager()->createNamedQuery($name);
    }

    /** {@inheritdoc} */
    public function createNativeQuery($sql, ResultSetMapping $rsm)
    {
        return $this->getEntityManager()->createNativeQuery($sql, $rsm);
    }

    /** {@inheritdoc} */
    public function createNamedNativeQuery($name)
    {
        return $this->getEntityManager()->createNamedNativeQuery($name);
    }

    /** {@inheritdoc} */
    public function createQueryBuilder()
    {
        return $this->getEntityManager()->createQueryBuilder();
    }

    /** {@inheritdoc} */
    public function getReference($entityName, $id)
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
    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        return $this->getEntityManager()->find($className, $id, $lockMode, $lockVersion);
    }

    /** {@inheritdoc} */
    public function flush($entity = null): void
    {
        $this->getEntityManager()->flush($entity);
    }

    /** {@inheritdoc} */
    public function getEventManager()
    {
        return $this->getEntityManager()->getEventManager();
    }

    /** {@inheritdoc} */
    public function getConfiguration()
    {
        return $this->getEntityManager()->getConfiguration();
    }

    /** {@inheritdoc} */
    public function isOpen()
    {
        return $this->getEntityManager()->isOpen();
    }

    /** {@inheritdoc} */
    public function getUnitOfWork()
    {
        return $this->getEntityManager()->getUnitOfWork();
    }

    /** {@inheritdoc} */
    public function getHydrator($hydrationMode)
    {
        return $this->getEntityManager()->getHydrator($hydrationMode);
    }

    /** {@inheritdoc} */
    public function newHydrator($hydrationMode)
    {
        return $this->getEntityManager()->newHydrator($hydrationMode);
    }

    /** {@inheritdoc} */
    public function getProxyFactory()
    {
        return $this->getEntityManager()->getProxyFactory();
    }

    /** {@inheritdoc} */
    public function getFilters()
    {
        return $this->getEntityManager()->getFilters();
    }

    /** {@inheritdoc} */
    public function isFiltersStateClean()
    {
        return $this->getEntityManager()->isFiltersStateClean();
    }

    /** {@inheritdoc} */
    public function hasFilters()
    {
        return $this->getEntityManager()->hasFilters();
    }

    /** {@inheritdoc} */
    public function getCache()
    {
        return $this->getEntityManager()->getCache();
    }

    /** {@inheritdoc} */
    public function getRepository($className)
    {
        return $this->getEntityManager()->getRepository($className);
    }

    /** {@inheritdoc} */
    public function getClassMetadata($className)
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
        $this->getEntityManager()->refresh($object);
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
    public function refresh($object)
    {
        return $this->getEntityManager()->refresh($object);
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
    public function getMetadataFactory()
    {
        return $this->getEntityManager()->getMetadataFactory();
    }
}
