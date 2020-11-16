<?php

namespace App\Messages\Models;

use App\Foundations\Persistence\Storable;
use App\Users\User;
use DateTime;

class Message implements Storable
{
    public const FIELD_ID = 'id';
    public const FIELD_SUBJECT = 'subject';
    public const FIELD_CREATED_BY = 'createdBy';

    public const COLUMN_ID = 'id';
    public const COLUMN_SUBJECT = 'subject';
    public const COLUMN_CONTENT = 'content';
    public const COLUMN_START_DATE  = 'start_date';
    public const COLUMN_EXPIRATION_DATE = 'expiration_date';
    public const COLUMN_CREATED_BY = 'created_by';
    public const COLUMN_DELETED = 'deleted';

    /** @var int */
    public $id;

    /** @var string */
    public $subject;

    /** @var string */
    public $content;

    /** @var DateTime */
    public $startDate;

    /** @var DateTime|null */
    public $expirationDate;

    /** @var int */
    public $createdBy;

    /** @var array */
    private $related = [];

    /**
     * @inheritDoc
     */
    public function getStorableFields(): array
    {
        return [
            self::COLUMN_ID => $this->id,
            self::COLUMN_SUBJECT => $this->subject,
            self::COLUMN_CONTENT => $this->content,
            self::COLUMN_START_DATE => $this->startDate,
            self::COLUMN_EXPIRATION_DATE => $this->expirationDate,
            self::COLUMN_CREATED_BY => $this->createdBy,
        ];
    }

    /**
     * @return User|null
     */
    public function getCreatedBy(): ?User
    {
        return $this->related[self::FIELD_CREATED_BY] ?? null;
    }

    /**
     * @param User $user
     */
    public function setCreatedBy(User $user): void
    {
        $this->related[self::FIELD_CREATED_BY] = $user;
        $this->createdBy = $user->id;
    }
}
