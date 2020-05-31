<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 12:52
 */

namespace App\Http\Controllers\Api;


use App\DDD\Todo\Exception\AlreadyCompletedException;
use App\DDD\Todo\Exception\CannotChangeCompletedTodoException;
use App\DDD\Todo\Exception\ValidationException;
use App\DDD\Todo\Resources\TodoResource;
use App\DDD\Todo\Services\TodoService;
use App\DDD\User\Resources\UserResource;
use App\Http\Controllers\BaseApiController;
use App\Models\Todo;
use App\Policies\TodoPolicy;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoController extends BaseApiController
{
    /**
     * @var TodoService
     */
    private $service;

    public function __construct(TodoService $service)
    {
        $this->service = $service;

        $this->authorizeResource(Todo::class, 'todo');
    }

    public function index(Request $request, User $user)
    {
        $todoList = $this->service->getListForUser($user);
        return $this->respondWithSuccess(TodoResource::collection($todoList));
    }

    public function store(Request $request, User $user)
    {
        try {
            $todoList = $this->service->create($request->all(), $user);
        } catch (ValidationException $exception) {
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($exception->getErrors());
        }

        return $this->respondWithSuccess(new TodoResource($todoList));
    }

    public function update(Request $request, User $user, Todo $todo)
    {

        try {
            $todoList = $this->service->update($request->all(), $todo->id);
        } catch (ValidationException $exception) {
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($exception->getErrors());
        } catch (ModelNotFoundException $exception) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError($exception->getMessage());
        }

        return $this->respondWithSuccess(new TodoResource($todoList));
    }

    public function makeCompleted(User $user, Todo $todo)
    {
        try {
            $todoList = $this->service->makeCompleted($todo->id);
        } catch (AlreadyCompletedException $exception) {
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($exception->getErrors());
        } catch (ModelNotFoundException $exception) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError($exception->getMessage());
        }

        return $this->respondWithSuccess(new TodoResource($todoList));
    }

    public function destroy(User $user, Todo $todo)
    {
        try {
            $this->service->delete($todo->id);
        } catch (ModelNotFoundException $exception) {
            return $this->setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError($exception->getMessage());
        } catch (ValidationException $exception) {
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($exception->getErrors());
        }

        return $this->respondWithSuccess('Deleted successfully');
    }
}
