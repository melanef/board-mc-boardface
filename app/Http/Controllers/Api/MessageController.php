<?php

namespace App\Http\Controllers\Api;

use App\Foundations\Persistence\NotFoundException;
use App\Http\Controllers\Controller;
use App\Messages\Contexts\FindMessageContext;
use App\Messages\Models\Message;
use App\Messages\Services\MessageService;
use App\Users\User;
use DateTime;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    private const PARAM_SUBJECT = '_subject';
    private const PARAM_CONTENT = '_content';
    private const PARAM_START_DATE = '_startDate';
    private const PARAM_EXPIRATION_DATE = '_expirationDate';
    private const PARAM_CREATED_BY = '_createdBy';
    private const PARAM_USER_ID = '_id';

    /** @var MessageService */
    private $service;

    /**
     * MessageController constructor.
     *
     * @param MessageService $service
     */
    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $context = new FindMessageContext();

        if ($request->has(self::PARAM_CREATED_BY)) {
            $context->createdBy = $request->input(self::PARAM_CREATED_BY);
        }

        return new JsonResponse($this->service->find($context));
    }

    /**
     * @param int $id
     *
     * @return Response|JsonResponse
     */
    public function show(int $id): Response
    {
        try {
            return new JsonResponse($this->service->findOneById($id));
        } catch (NotFoundException $exception) {
            return new Response($exception->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): Response
    {
        $message = new Message();

        try {
            $message->subject = $request->input(self::PARAM_SUBJECT);
            $message->content = $request->input(self::PARAM_CONTENT);
            $message->setCreatedBy(User::find($request->input(self::PARAM_CREATED_BY)[self::PARAM_USER_ID]));
            $message->startDate = new DateTime($request->input(self::PARAM_START_DATE));
            $message->expirationDate = $request->has(self::PARAM_EXPIRATION_DATE) && !empty($request->input(self::PARAM_EXPIRATION_DATE))
                ? new DateTime($request->input(self::PARAM_EXPIRATION_DATE)) : null;

            $message = $this->service->createOne($message);
        } catch (Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($message);
    }

    /**
     * @param Request $request
     * @param int     $id
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id): Response
    {
        $message = $this->service->findOneById($id);

        try {
            $message->subject = $request->input(self::PARAM_SUBJECT);
            $message->content = $request->input(self::PARAM_CONTENT);
            $message->startDate = new DateTime($request->input(self::PARAM_START_DATE));
            $message->expirationDate = new DateTime($request->input(self::PARAM_EXPIRATION_DATE));
        } catch (Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($this->service->updateOne($message));
    }

    /**
     * @param int $id
     *
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $this->service->deleteOneById($id);

        return new Response();
    }
}
