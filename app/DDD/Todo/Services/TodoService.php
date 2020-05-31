<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 12:49
 */

namespace App\DDD\Todo\Services;


use App\DDD\Todo\Exception\AlreadyCompletedException;
use App\DDD\Todo\Exception\CannotChangeCompletedTodoException;
use App\DDD\Todo\Exception\ValidationException;
use App\DDD\Todo\Repos\TodoRepo;
use App\DDD\Todo\Vo\TodoVo;
use App\Models\Todo;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TodoService
{
    /**
     * @var TodoRepo
     */
    private $repo;

    public function __construct(TodoRepo $repo)
    {
        $this->repo = $repo;
    }

    /**
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getListForUser(User $user = null, $perPage = 10)
    {
        if (!$user) {
            return [];
        }
        return $this->repo->getListForUser($user, $perPage);
    }

    /**
     * @param $data
     * @param User $user
     * @throws ValidationException
     * @return Todo
     */
    public function create($data, User $user)
    {
        $vo = new TodoVo($data['task'] ?? '');

        $model = new Todo();

        $model->fill($vo->toArray());

        $model = $this->repo->create($user, $model);
        return $model;
    }

    /**
     * @param $data
     * @param $id
     * @return Todo
     * @throws ValidationException
     */
    public function update($data, $id)
    {

        $model = $this->find($id);

        if ($model->isCompleted()) {
            throw new CannotChangeCompletedTodoException();
        }

        $vo = new TodoVo($data['task'] ?? '', $data['completed'] ?? $model->status);

        $model->fill($vo->toArray());

        $model = $this->repo->update($model);
        return $model;

    }

    /**
     * @param $id
     * @return Todo
     * @throws AlreadyCompletedException
     */
    public function makeCompleted($id)
    {
        $model = $this->find($id);

        if ($model->isCompleted()) {
            throw new AlreadyCompletedException();
        }

        $model->status = Todo::STATUS_COMPLETED;

        return $this->repo->update($model);
    }

    /**
     * @param $id
     * @return bool
     * @throws AlreadyCompletedException
     */
    public function delete($id)
    {
        $model = $this->find($id);
        if ($model->isCompleted()) {
            throw new AlreadyCompletedException();
        }
        $this->repo->delete($model);

        return true;
    }

    /**
     * @param $id
     * @return Todo
     * @throws ModelNotFoundException
     */
    public function find($id)
    {
        return $this->repo->findById($id);
    }
}
