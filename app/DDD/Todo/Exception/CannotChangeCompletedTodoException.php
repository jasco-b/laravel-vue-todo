<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 13:51
 */

namespace App\DDD\Todo\Exception;


use Throwable;

class CannotChangeCompletedTodoException extends ValidationException
{
    public function __construct($error = 'You cannot change completed task', string $message = "Validation error", int $code = 0, Throwable $previous = null)
    {
        $errors = [];
        $errors['status'] = $error;

        parent::__construct($errors, $message, $code, $previous);
    }
}
