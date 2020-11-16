<?php

namespace App\Foundations\Persistence;

use Closure;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Query\Builder;
use InvalidArgumentException;
use JsonMapper\Enums\TextNotation;
use JsonMapper\JsonMapper;
use JsonMapper\JsonMapperFactory;
use JsonMapper\Middleware\CaseConversion;

abstract class BaseRepository
{
    private const COLUMN_DELETED = 'deleted';

    /** @var DatabaseManager */
    private $databaseManager;

    /** @var JsonMapper */
    private $mapper;

    /**
     * BaseRepository constructor.
     *
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;

        $this->mapper = (new JsonMapperFactory())->bestFit();
        $this->mapper->push(new CaseConversion(
            TextNotation::UNDERSCORE(),
            TextNotation::CAMEL_CASE()
        ));
    }

    /**
     * @return string
     */
    abstract public function getEntityClass(): string;

    /**
     * @return string
     */
    abstract public function getTableName(): string;

    /**
     * @return string
     */
    abstract public function getTableAlias(): string;

    /**
     * @return string
     */
    abstract public function getPrimaryKey(): string;

    /**
     * @param Closure|null $configureQuery
     *
     * @return object|null
     */
    public function findOneOrNull(Closure $configureQuery = null): ?object
    {
        $alias = $this->getTableAlias();
        $query = $this->databaseManager->query()
            ->select([sprintf('%s.*', $alias)])
            ->from($this->getTableName(), $alias);

        if ($configureQuery) {
            $configureQuery($query);
        }

        $row = $query->first();

        if (!$row) {
            return null;
        }

        $entityClass = $this->getEntityClass();
        $entity = new $entityClass();
        $this->mapper->mapObject($row, $entity);
        $entity = $this->unpackOne($entity);

        return $entity;
    }

    /**
     * @param Closure|null $configureQuery
     *
     * @return object[]
     */
    public function find(Closure $configureQuery = null): array
    {
        $alias = $this->getTableAlias();

        $query = $this->databaseManager->query()
            ->select()
            ->from($this->getTableName(), $alias);

        if ($configureQuery) {
            $configureQuery($query);
        }

        $rows = $query->get();

        $entities = [];
        $entityClass = $this->getEntityClass();
        foreach ($rows as $row) {
            $entity = new $entityClass();
            $this->mapper->mapObject($row, $entity);
            $entity = $this->unpackOne($entity);

            $entities[] = $entity;
        }

        return $entities;
    }

    /**
     * @param Closure|null $configureQuery
     *
     * @return object
     */
    public function findOne(Closure $configureQuery = null): object
    {
        $entity = $this->findOneOrNull($configureQuery);
        if (!$entity) {
            throw new NotFoundException($this->getEntityClass());
        }

        return $entity;
    }

    /**
     * @param $id
     *
     * @return object
     * @throws NotFoundException
     */
    public function findOneById($id): object
    {
        return $this->findOne(function (Builder $query) use ($id) {
            $query->where(sprintf('%s.%s', $this->getTableAlias(), $this->getPrimaryKey()), $id);
        });
    }

    /**
     * @param Storable $entity
     *
     * @return Storable
     */
    public function createOne(Storable $entity): Storable
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity is null');
        }

        $entity = $this->prepareOne($entity);

        $entity = $this->packOne($entity);

        $query = $this->databaseManager->query()->from($this->getTableName());

        $newId = $query->insertGetId($entity->getStorableFields());
        $newEntity = clone $entity;

        $idField = $this->getPrimaryKey();
        if (!isset($newEntity->$idField)) {
            $newEntity->$idField = $newId;
        }

        return $this->unpackOne($newEntity);
    }

    /**
     * @param Storable $entity
     *
     * @return Storable
     */
    public function updateOne(Storable $entity): Storable
    {
        if (empty($entity)) {
            throw new InvalidArgumentException('Entity is null');
        }

        $entity = $this->prepareOne($entity);

        $entity = $this->packOne($entity);

        $idField = $this->getPrimaryKey();

        $query = $this->databaseManager->query()
            ->from($this->getTableName())
            ->where($this->getPrimaryKey(), $entity->$idField);

        $query->update($entity->getStorableFields());
        $newEntity = clone $entity;

        return $this->unpackOne($newEntity);
    }

    /**
     * @param $id
     */
    public function deleteOneById($id): void
    {
        $this->databaseManager->query()
            ->from($this->getTableName())
            ->where($this->getPrimaryKey(), $id)
            ->update([self::COLUMN_DELETED => true]);
    }

    /**
     * @param Storable $entity
     *
     * @return Storable
     */
    protected function packOne(Storable $entity): Storable
    {
        return $entity;
    }

    /**
     * @param Storable $entity
     *
     * @return Storable
     */
    protected function unpackOne(Storable $entity): Storable
    {
        return $entity;
    }

    /**
     * @param Storable $entity
     *
     * @return Storable
     */
    protected function prepareOne(Storable $entity): Storable
    {
        return $entity;
    }
}
