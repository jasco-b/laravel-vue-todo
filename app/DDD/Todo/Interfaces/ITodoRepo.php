<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 16:02
 */

namespace App\DDD\Todo\Interfaces;


use App\Models\Todo;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface ITodoRepo
{
    /**
     * @param $id
     * @return Todo
     * @throws ModelNotFoundException
     */
    public function findById($id);

    /**
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getListForUser(User $user, $perPage = 10);

    /**
     * @param User $user
     * @param Todo $todo
     * @return Todo
     */
    public function create(User $user, Todo $todo);

    /**
     * @param Todo $todo
     * @return Todo
     */
    public function update(Todo $todo);

    /**
     * @param Todo $model
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Todo $model);
}
