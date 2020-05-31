<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-23
 * Time: 19:52
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\BaseApiController;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends BaseApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)
            ->respondWithError('Unauthorized');
    }


    /**
     * Log the user out (Invalidate the token)
     *
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->respondWithSuccess(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
