<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 12:45
 */

namespace App\DDD\Todo\Exception;


use Throwable;

class ValidationException extends BaseException
{
    /**
     * @var array
     */
    private $errors;

    public function __construct($errors = [], string $message = "Validation error", int $code = 0, Throwable $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


}
