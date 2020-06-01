<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-06-01
 * Time: 11:11
 */

namespace App\DDD\User\ServiceProviders;


use App\DDD\User\Interfaces\IUserRepo;
use App\DDD\User\Repos\UserRepo;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IUserRepo::class, UserRepo::class);
    }
}
