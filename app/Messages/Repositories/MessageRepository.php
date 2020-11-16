<?php

namespace App\Messages\Repositories;

use App\Foundations\Persistence\BaseRepository;
use App\Foundations\Persistence\Storable;
use App\Messages\Contexts\FindMessageContext;
use App\Messages\Models\Message;
use App\Users\User;
use Closure;
use Illuminate\Database\Query\Builder;

class MessageRepository extends BaseRepository
{
    private const TABLE_NAME = 'messages';
    private const TABLE_ALIAS = 'm';

    /**
     * @inheritDoc
     */
    public function getEntityClass(): string
    {
        return Message::class;
    }

    /**
     * @inheritDoc
     */
    public function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    /**
     * @inheritDoc
     */
    public function getTableAlias(): string
    {
        return self::TABLE_ALIAS;
    }

    /**
     * @inheritDoc
     */
    public function getPrimaryKey(): string
    {
        return Message::COLUMN_ID;
    }

    public function find(Closure $configureQuery = null): array
    {
        return parent::find(function (Builder $query) use ($configureQuery) {
            $query->where(Message::COLUMN_DELETED, false);

            if ($configureQuery) {
                $configureQuery($query);
            }
        });
    }

    /**
     * @param FindMessageContext $context
     *
     * @return Message[]
     */
    public function findByContext(FindMessageContext $context): array
    {
        return $this->find(function (Builder $query) use ($context) {
            if (!empty($context->subjectQuery)) {
                $query->where(Message::COLUMN_SUBJECT, 'LIKE', sprintf('%%%s%%', $context->subjectQuery));
            }

            if (!empty($context->createdBy)) {
                $query->where(Message::COLUMN_CREATED_BY, $context->createdBy);
            }

            if (!empty($context->expirationDate)) {
                $query->where(Message::COLUMN_EXPIRATION_DATE, '<=', $context->expirationDate->format('Y-m-d H:i:s'));
            }
        });
    }

    protected function packOne(Storable $entity): Storable
    {
        if ($entity->createdBy instanceof User) {
            $entity->createdBy = $entity->createdBy->id;
        }

        return parent::packOne($entity);
    }

    protected function unpackOne(Storable $entity): Storable
    {
        if ($entity->createdBy && empty($entity->getCreatedBy())) {
            $entity->setCreatedBy(User::find($entity->createdBy));
            $entity->createdBy = $entity->getCreatedBy();
        }

        return parent::unpackOne($entity);
    }
}
