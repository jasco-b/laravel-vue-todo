<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 12:48
 */

namespace App\DDD\Todo\Vo;


use App\DDD\Todo\Exception\ValidationException;
use App\Models\Todo;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TodoVo implements Arrayable
{
    /**
     * @var $task string
     */
    private $task;
    /**
     * @var int
     */
    private $status;

    /**
     * TodoVo constructor.
     * @param $task
     * @param int $status
     * @throws ValidationException
     */
    public function __construct($task, $status = Todo::STATUS_PENDING)
    {
        $this->validate(compact('task', 'status'));
        $this->task = $task;
        $this->status = $status;
    }

    public function validate($data)
    {
        $validator = Validator::make($data, [
            'task' => 'required|string',
            'status' => [
                'required',
                Rule::in(array_keys(Todo::statuses())),
            ]
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function toArray()
    {
        return [
            'task' => $this->getTask(),
            'status' => $this->getStatus(),
        ];
    }

}
