<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-06-01
 * Time: 11:04
 */

namespace App\DDD\Todo\ServiceProvider;


use App\DDD\Todo\Interfaces\ITodoRepo;
use App\DDD\Todo\Repos\TodoRepo;
use Illuminate\Support\ServiceProvider;

class TodoProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ITodoRepo::class, TodoRepo::class);

    }
}
