<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 14:54
 */

namespace App\DDD\User\Services;


use App\DDD\User\Repos\UserRepo;
use App\DDD\User\Vo\UserVo;
use App\User;

class UserService
{
    /**
     * @var UserRepo
     */
    private $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param $id
     * @return User
     */
    public function find($id)
    {
        return $this->userRepo->findById($id);
    }

    public function save($data, $user_id)
    {
        $model = $this->find($user_id);

        $vo = new UserVo(
            $data['name'] ?? '',
            $data['email'] ?? '',
            $data['password'] ?? ''
        );

        $model->name = $vo->getName();
        $model->email = $vo->getEmail();

        if ($vo->isPasswordChanged()) {
            $model->setPassword($vo->getPassword());
        }

        return $this->userRepo->save($model);
    }
}
