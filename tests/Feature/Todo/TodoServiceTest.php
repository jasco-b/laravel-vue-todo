<?php

namespace Tests\Feature\Todo;

use App\DDD\Todo\Exception\AlreadyCompletedException;
use App\DDD\Todo\Exception\CannotChangeCompletedTodoException;
use App\DDD\Todo\Exception\ValidationException;
use App\DDD\Todo\Repos\TodoRepo;
use App\DDD\Todo\Services\TodoService;
use App\Models\Todo;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoServiceTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;


    public function getService()
    {
        $repo = new TodoRepo();

        return new TodoService($repo);
    }

    public function createTodo($status = Todo::STATUS_PENDING)
    {
        $user = factory(User::class, 1)->create()[0];
        $todo = factory(Todo::class, 1)->make()[0];
        $todo->status = $status;
        $user->todos()->save($todo);
        return $todo;
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        $users = factory(User::class, 3)->create()->each(function ($user) {
            /**
             * @var $user User
             */
            $user->todos()->saveMany(factory(Todo::class, 2)->make());
        });

        $service = $this->getService();

        $user = $users[0];

        $todos = $service->getListForUser($user);

        $this->assertEquals(2, $todos->count());

        $this->assertEquals($todos[0]->user_id??-1, $user->id);
    }

    public function testCreateWithValidData()
    {
        $user = factory(User::class, 1)->create()[0];

        $task = 'This is task';
        $data = [
            'task' => $task
        ];

        $service = $this->getService();

        $todo = $service->create($data, $user);

        $this->assertEquals($todo->task, $task);
        $this->assertEquals($todo->user_id, $user->id);


        $this->assertDatabaseHas('todos', [
            'id'=>$todo->id,
            'status'=>Todo::STATUS_PENDING,
        ]);

    }

    public function testTryCreateWithInValidData()
    {
        $user = factory(User::class, 1)->create()[0];

        $task = '';
        $data = [
            'task' => $task
        ];

        $service = $this->getService();

        $this->expectException(ValidationException::class);

        $service->create($data, $user);
    }

    public function testUpdateWithValidData()
    {
        $task = $this->createTodo();


        $service = $this->getService();

        $taskText = 'This is task';
        $data = [
            'task' => $taskText
        ];

        $updatedTask = $service->update($data, $task->id);

        $this->assertEquals($updatedTask->task, $taskText);
    }

    public function testUpdateWithInvalidData()
    {
        $task = $this->createTodo();


        $service = $this->getService();

        $taskText = '';
        $data = [
            'task' => $taskText
        ];

        $this->expectException(ValidationException::class);
        $service->update($data, $task->id);


        $data = [
            'task' => 'asdfasdfasdf',
            'status' => -1,
        ];

        $this->expectException(ValidationException::class);
        $service->update($data, $task->id);

    }

    public function testTryToUpdateCompletedTodo()
    {
        $task = $this->createTodo(Todo::STATUS_COMPLETED);


        $service = $this->getService();

        $taskText = 'This is task';
        $data = [
            'task' => $taskText
        ];

        $this->expectException(CannotChangeCompletedTodoException::class);
        $service->update($data, $task->id);
    }

    public function testTryToDelete()
    {
        $task = $this->createTodo();
        $service = $this->getService();


        $service->delete($task->id);

        $this->assertDatabaseMissing('todos', [
            'id'=>$task->id,
        ]);
    }

    public function testTryToChangeStatusWhichIsPending()
    {
        $task = $this->createTodo();


        $service = $this->getService();


        $task = $service->makeCompleted($task->id);

        $this->assertEquals($task->status, Todo::STATUS_COMPLETED);

        $this->assertDatabaseHas('todos', [
            'id' => $task->id,
            'status' => Todo::STATUS_COMPLETED,
        ]);
    }

    public function testTryTodoChangeStatusWhichIsCompleted()
    {
        $task = $this->createTodo(Todo::STATUS_COMPLETED);


        $service = $this->getService();


        $this->expectException(AlreadyCompletedException::class);
        $service->makeCompleted($task->id);
    }


}
