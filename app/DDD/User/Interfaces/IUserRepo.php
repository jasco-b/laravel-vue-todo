<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-06-01
 * Time: 11:07
 */

namespace App\DDD\User\Interfaces;


use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface IUserRepo
{
    /**
     * @param $id
     * @return User
     * @throws ModelNotFoundException
     */
    public function findById($id);

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user);
}
