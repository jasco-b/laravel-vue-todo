<?php

namespace Tests\Unit\Todo;

use App\DDD\Todo\Exception\ValidationException;
use App\DDD\Todo\Vo\TodoVo;
use App\Models\Todo;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class TodoVoTest extends TestCase
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
        $task = 'this is test task';
        $status = Todo::STATUS_PENDING;

        $vo = new TodoVo($task, $status);

        $this->assertEquals($vo->getStatus(), $status);

        $this->assertEquals($vo->getTask(), $task);

    }

    public function testNotValidTask()
    {
        $task = '';
        $status = Todo::STATUS_PENDING;

        $this->expectException(ValidationException::class);
        $vo = new TodoVo($task, $status);
    }

    public function testNotValidTaskStatus()
    {
        $task = 'Task test';
        $status = -1;

        $this->expectException(ValidationException::class);
        $vo = new TodoVo($task, $status);
    }

}
