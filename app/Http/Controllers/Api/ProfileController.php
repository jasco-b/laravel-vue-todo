<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 15:34
 */

namespace App\Http\Controllers\Api;


use App\DDD\User\Exceptions\ValidationException;
use App\DDD\User\Resources\UserResource;
use App\DDD\User\Services\UserService;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProfileController extends BaseApiController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function show()
    {
        $user = auth()->user();

        return $this->respondWithSuccess(new UserResource($user));
    }

    public function update(UserRequest $request)
    {

        try {
            $model = $this->userService->save($request->validated(), auth()->user()->id);
        } catch (ValidationException $exception) {
            return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError($exception->getErrors());
        }

        return $this->respondWithSuccess(new UserResource($model));
    }
}
