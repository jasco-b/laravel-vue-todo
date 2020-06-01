<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 14:53
 */

namespace App\DDD\User\Repos;


use App\DDD\User\Interfaces\IUserRepo;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepo implements IUserRepo
{
    /**
     * @param $id
     * @return User
     * @throws ModelNotFoundException
     */
    public function findById($id)
    {
        return User::query()->findOrFail($id);
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user)
    {
        $user->save();
        return $user;
    }
}
