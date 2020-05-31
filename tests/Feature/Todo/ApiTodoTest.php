<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\Feature\Traits\CreateTodoTrait;
use Tests\Feature\Traits\GetTokenTrait;
use Tests\Feature\Traits\RequestWIthHeaderTrait;
use Tests\TestCase;

class ApiTodoTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use CreateTodoTrait;
    use GetTokenTrait;
    use RequestWIthHeaderTrait;


    public function testWithoutAuthHeader()
    {
        $this->createTodo(5);
        $todos2 = $this->createTodo(2);


        $response = $this->request()
            ->get(route('api.users.tasks.index', [
                    'user' => $todos2[0]['user_id'],
                ])
            );

        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testList()
    {
        $this->createTodo(5);
        $todos2 = $this->createTodo(2);

        $response = $this->request($todos2[0]['user'])
            ->get(route('api.users.tasks.index', [
                    'user' => $todos2[0]['user_id'],
                ])
            );

        $response->assertStatus(200);

        $response->assertJsonCount(2, 'data');

    }


    public function testCreateWithValidData()
    {
        $todos2 = $this->createTodo(2);
        $user = $todos2[0]['user'];
        $requestData = [
            'task' => 'my new task ' . time(),
        ];

        $response = $this->request($user)
            ->postJson(route('api.users.tasks.store', [
                'user' => $user['id'],
            ]),
                $requestData
            );

        $response->assertStatus(200);
        $requestData['status'] = Todo::STATUS_PENDING;

        $response->assertJsonFragment(
            $requestData
        );

        $requestData['user_id'] = $user->id;


        $this->assertDatabaseHas('todos', $requestData);

        $this->assertDatabaseCount('todos', 3);
    }

    public function testTryCreateWithInValidData()
    {
        $todos2 = $this->createTodo(2);
        $user = $todos2[0]['user'];
        $requestData = [
            'task' => '',
        ];

        $response = $this->request($user)
            ->post(route('api.users.tasks.store', [
                'user' => $todos2[0]['user_id'],
            ]),
                $requestData
            );

        $response->assertStatus(422);

        $response->assertJsonFragment(
            [
                'errors' => [
                    'task' => ['The task field is required.']
                ],
            ]
        );

    }


    public function testUpdateWithInvalidData()
    {
        $todos2 = $this->createTodo(2);
        $todo = $todos2[0];
        $user = $todo['user'];


        $requestData = [
            'task' => '',
        ];

        $this->assertEquals($todo['status'], Todo::STATUS_PENDING);

        $response = $this->request($user)
            ->putJson(route('api.users.tasks.update', [
                'user' => $user['id'],
                'todo' => $todo['id'],
            ]),
                $requestData
            );

        $response->assertStatus(422);

        $response->assertJsonFragment(
            [
                'errors' => [
                    'task' => ['The task field is required.']
                ],
            ]
        );

        $this->assertDatabaseCount('todos', 2);


    }

    public function testTryToUpdateCompletedTodo()
    {
        $todos2 = $this->createTodo(1, Todo::STATUS_COMPLETED);
        $todo = $todos2[0];
        $user = $todo['user'];

        $response = $this->request($user)
            ->putJson(route('api.users.tasks.complete', [
                    'user' => $todos2[0]['user_id'],
                    'todo' => $todo['id'],
                ])
            );

        $response->assertStatus(422);

        $response->assertJsonFragment(
            [
                "errors" => [
                    "status" => "Task has been completed already"
                ]

            ]
        );


    }

    public function testTryToDelete()
    {
        $todos2 = $this->createTodo(1);
        $todo = $todos2[0];
        $user = $todo['user'];


        $response = $this->request($user)
            ->delete(route('api.users.tasks.destroy', [
                    'user' => $todos2[0]['user_id'],
                    'todo' => $todo['id'],
                ])
            );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    public function testTryToChangeStatusWhichIsPending()
    {
        $todos2 = $this->createTodo(1);
        $todo = $todos2[0];
        $user = $todo['user'];


        $response = $this->request($user)
            ->put(route('api.users.tasks.complete', [
                    'user' => $todos2[0]['user_id'],
                    'todo' => $todo['id'],
                ])
            );

        $response->assertStatus(200);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'status' => Todo::STATUS_COMPLETED,
            'task' => $todo['task'],
        ]);
    }

    public function testTryTodoChangeStatusWhichIsCompleted()
    {
        $todos2 = $this->createTodo(1, Todo::STATUS_COMPLETED);
        $todo = $todos2[0];
        $user = $todo['user'];


        $response = $this->request($user)
            ->put(route('api.users.tasks.complete', [
                    'user' => $todos2[0]['user_id'],
                    'todo' => $todo['id'],
                ])
            );

        $response->assertStatus(422);

        $response->assertJsonFragment([
            'errors' => [
                'status' => 'Task has been completed already'
            ]
        ]);
    }

    public function testUpdateWithValidData()
    {
        $todos2 = $this->createTodo(2);
        $todo = $todos2[0];
        $user = $todo['user'];

        $this->assertEquals($todo['status'], Todo::STATUS_PENDING);

        $requestData = [
            'task' => 'my new task ' . time(),
        ];

        $response = $this->request($user)
            ->putJson(route('api.users.tasks.update', [
                'user' => $todos2[0]['user_id'],
                'todo' => $todo['id'],
            ]),
                $requestData
            );


        $response->assertStatus(200);
        $requestData['status'] = Todo::STATUS_PENDING;
        $requestData['id'] = $todo->id;

        $response->assertJsonFragment(
            $requestData
        );

        $requestData['user_id'] = $user->id;
        $requestData['id'] = $todo->id;


        $this->assertDatabaseHas('todos', $requestData);
    }


}
