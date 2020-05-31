<?php

namespace Tests\Unit\Users;

use App\DDD\User\Exceptions\ValidationException;
use App\DDD\User\Vo\UserVo;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class UserVoTest extends TestCase
{

    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testValidData()
    {
        $mail = 'test@asdf.com';
        $name = 'no name';
        $password = 'this is test';

        $vo = new UserVo($name, $mail, $password);

        $this->assertEquals($vo->getName(), $name);

        $this->assertEquals($vo->getEmail(), $mail);

        $this->assertEquals($vo->getPassword(), $password);
        $this->assertEquals($vo->isPasswordChanged(), true);

        $vo = new UserVo($name, $mail);

        $this->assertEquals($vo->getName(), $name);

        $this->assertEquals($vo->getEmail(), $mail);

        $this->assertEquals($vo->getPassword(), null);
        $this->assertEquals($vo->isPasswordChanged(), false);

    }

    public function testNotValidEmail()
    {
        $mail = 'asdfs';
        $name = 'no name';

        $this->expectException(ValidationException::class);

        $vo = new UserVo($name, $mail);
    }

    public function testEmptyEmail()
    {
        $mail = '';
        $name = 'no name';

        $this->expectException(ValidationException::class);

        $vo = new UserVo($name, $mail);
    }

    public function testNotValidName()
    {
        $mail = 'test@asdf.com';
        $name = '';
        $password = 'this is test';
        $this->expectException(ValidationException::class);

        $vo = new UserVo($name, $mail, $password);

    }
}
