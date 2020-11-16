<?php

namespace App\Messages\Services;

use App\Foundations\Persistence\NotFoundException;
use App\Messages\Contexts\FindMessageContext;
use App\Messages\Models\Message;
use App\Messages\Repositories\MessageRepository;

class MessageService
{
    /** @var MessageRepository */
    private $repository;

    /**
     * MessageService constructor.
     *
     * @param MessageRepository $repository
     */
    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FindMessageContext $context
     *
     * @return Message[]
     */
    public function find(FindMessageContext $context): array
    {
        return $this->repository->findByContext($context);
    }

    /**
     * @param int $id
     *
     * @return Message
     * @throws NotFoundException
     */
    public function findOneById(int $id): object
    {
        return $this->repository->findOneById($id);
    }

    /**
     * @param Message $message
     *
     * @return Message
     */
    public function createOne(Message $message): object
    {
        return $this->repository->createOne($message);
    }

    /**
     * @param Message $message
     *
     * @return Message
     */
    public function updateOne(Message $message): object
    {
        return $this->repository->updateOne($message);
    }

    /**
     * @param int $id
     */
    public function deleteOneById(int $id): void
    {
        $this->repository->deleteOneById($id);
    }
}
