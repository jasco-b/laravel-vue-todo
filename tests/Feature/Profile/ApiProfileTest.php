<?php

namespace Tests\Feature\Profile;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\CreatesApplication;
use Tests\TestCase;

class ApiProfileTest extends TestCase
{
    use DatabaseMigrations;
    use CreatesApplication;
    use RefreshDatabase;


    public function createUser()
    {
        return factory(User::class, 1)->create()[0];
    }

    public function getToken($user)
    {
        return Auth::guard()->attempt([
            'email' => $user->email,
            'password' => 'password',
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testProfileInfo()
    {
        $user = $this->createUser();

        $token = $this->getToken($user);

        $response = $this->withHeaders([
            'Authorization' => 'bearer ' . $token,
        ])->get('/api/profile');

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ]
        ]);
    }

    public function testProfileUpdateWithoutPassword()
    {
        $user = $this->createUser();

        $token = $this->getToken($user);

        $name = 'new Name bro';
        $email = 'notFound@mail.notfound';

        $response = $this->withHeaders([
            'Authorization' => 'bearer ' . $token,
        ])->json('PUT', '/api/profile', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email,
            'name' => $name,
        ]);
    }

    public function testProfileUpdateWithPassword()
    {
        $user = $this->createUser();

        $token = $this->getToken($user);

        $name = 'new Name bro';
        $email = 'notFound@mail.notfound';
        $password = 'test123';

        $response = $this->withHeaders([
            'Authorization' => 'bearer ' . $token,
        ])->json('PUT', '/api/profile', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $email,
            'name' => $name,
        ]);

        $foundUser = User::find($user->id);

        $hasher = app('hash');
        $this->assertTrue($hasher->check($password, $foundUser->password));
    }


    public function testProfileUpdateWithNoName()
    {
        $user = $this->createUser();

        $token = $this->getToken($user);

        $name = '';
        $email = 'notFound@mail.notfound';

        $response = $this->withHeaders([
            'Authorization' => 'bearer ' . $token,
        ])->json('PUT', '/api/profile', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(422);

        $response->assertJsonFragment(['errors' => [
            'name' => [
                'The name field is required.'
            ]
        ]]);

    }

    public function testProfileUpdateWithInvalidMail()
    {
        $user = $this->createUser();

        $token = $this->getToken($user);

        $name = 'no name';
        $email = 'notFound';

        $response = $this->withHeaders([
            'Authorization' => 'bearer ' . $token,
        ])->json('PUT', '/api/profile', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'errors' => [
                'email' => [
                    'The email must be a valid email address.'
                ]
            ]
        ]);

    }

}
