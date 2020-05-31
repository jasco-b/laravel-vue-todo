<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-25
 * Time: 20:29
 */

namespace Tests\Feature\Traits;


use App\Models\Todo;
use App\User;

trait CreateTodoTrait
{
    public function createTodo($number = 1, $status = Todo::STATUS_PENDING)
    {
        /**
         * @var $user User
         */
        $user = factory(User::class, 1)->create()[0];
        $todos = factory(Todo::class, $number)->make();
        $todos->each(function ($todo) use ($status) {
            $todo->status = $status;
        });
        $user->todos()->saveMany($todos);
        return $todos;
    }
}
