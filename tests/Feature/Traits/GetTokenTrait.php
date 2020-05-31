<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-25
 * Time: 20:28
 */

namespace Tests\Feature\Traits;


use Illuminate\Support\Facades\Auth;

trait GetTokenTrait
{
    public function getToken($user)
    {
        return Auth::guard()->attempt([
            'email' => $user->email,
            'password' => 'password',
        ]);
    }
}
