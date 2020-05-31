<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 12:50
 */

namespace App\DDD\Todo\Repos;


use App\Models\Todo;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TodoRepo
{
    /**
     * @param $id
     * @return Todo
     * @throws ModelNotFoundException
     */
    public function findById($id)
    {
        return Todo::query()->findOrFail($id);
    }

    /**
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getListForUser(User $user, $perPage = 10)
    {
        return $user->todos()
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * @param User $user
     * @param Todo $todo
     * @return Todo
     */
    public function create(User $user, Todo $todo)
    {
        $user->todos()->save($todo);
        return $todo;
    }

    /**
     * @param Todo $todo
     * @return Todo
     */
    public function update(Todo $todo)
    {
        $todo->save();
        return $todo;
    }

    /**
     * @param Todo $model
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Todo $model)
    {
        return $model->delete();
    }
}
