<?php

namespace Tests\Feature\Profile;

use App\DDD\User\Exceptions\ValidationException;
use App\DDD\User\Repos\UserRepo;
use App\DDD\User\Services\UserService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CreatesApplication;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use RefreshDatabase;

    public function getService()
    {
        $repo = new UserRepo();

        return new UserService($repo);
    }

    public function createUser()
    {
        return factory(User::class, 1)->create()[0];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testUserUpdateWithValidData()
    {
        $user = $this->createUser();

        $service = $this->getService();

        $data = [
            'name' => 'Not found name111',
            'email' => 'notfoundemail111@mail.com',
        ];

        $user = $service->save($data, $user->id);

        $data['id'] = $user->id;
        $this->assertDatabaseHas('users', $data);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);

    }

    public function testUserUpdatePassword()
    {
        $user = $this->createUser();

        $service = $this->getService();

        $data = [
            'name' => 'Not found name111',
            'email' => 'notfoundemail111@mail.com',
            'password' => '123456',
        ];

        $user = $service->save($data, $user->id);

        $hasher = app('hash');
        $this->assertTrue($hasher->check($data['password'], $user->password));

        $data['id'] = $user->id;
        unset($data['password']);
        $this->assertDatabaseHas('users', $data);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    public function testWithInvalidMail()
    {
        $user = $this->createUser();

        $service = $this->getService();

        $data = [
            'name' => 'Not found name111',
            'email' => '',
        ];

        $this->expectException(ValidationException::class);
        $service->save($data, $user->id);
    }

    public function testWithInvalidName()
    {
        $user = $this->createUser();

        $service = $this->getService();

        $data = [
            'name' => '',
            'email' => 'notfoundemail111@mail.com',
        ];

        $this->expectException(ValidationException::class);
        $service->save($data, $user->id);
    }
}
