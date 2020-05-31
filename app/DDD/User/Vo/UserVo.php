<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 14:38
 */

namespace App\DDD\User\Vo;


use App\DDD\User\Exceptions\ValidationException;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

class UserVo implements Arrayable
{
    private $name;
    private $email;
    /**
     * @var null
     */
    private $password;

    /**
     * UserVo constructor.
     * @param $name
     * @param $email
     * @param null $password
     * @throws ValidationException
     */
    public function __construct($name, $email, $password = null)
    {
        $this->validate(compact('name', 'email', 'password'));
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function isPasswordChanged(): bool
    {
        return trim($this->password) !== '';
    }

    public function validate($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
            ],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator->errors());
        }
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
        ];
    }
}
