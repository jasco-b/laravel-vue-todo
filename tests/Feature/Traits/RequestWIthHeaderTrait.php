<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-25
 * Time: 20:32
 */

namespace Tests\Feature\Traits;


use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;

trait RequestWIthHeaderTrait
{
    /**
     * @param null $user
     * @return MakesHttpRequests
     */
    public function request($user = null)
    {
        return $this->withHeaders([
            'accept' => 'Application/json',
            'Authentication' => $user ? $this->getTokenForUser($user) : $user,
        ]);
    }

    public function getTokenForUser($user)
    {
        if (method_exists($this, 'getToken')) {
            return $this->getToken($user);
        }

        return null;
    }
}
