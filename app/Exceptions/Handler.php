<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($request->wantsJson()) {
            return $this->handleApiException($request, $exception);
        }
        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception)
    {

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'success' => false,
                'errors' => $exception->errors(),
            ], $exception->getResponse() ? $exception->getResponse()->getStatusCode() : 422);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'errors' => [
                    $exception->getMessage(),
                ]
            ], 401);
        }

        if ($exception instanceof HttpResponseException) {
            return response()->json([
                'success' => false,
                'errors' => [
                    $exception->getMessage()
                ],
            ], $exception->getResponse()->getStatusCode());
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'errors' => [
                    $exception->getMessage()
                ],
            ], 405);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'errors' => [
                    'Page not found',
                ]
            ], 404);
        }

        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'errors' => [
                    $exception->getMessage(),
                ]
            ], 403);
        }

        return response()->json([
            'success' => false,
            'errors' => [
                $exception->getMessage(),
            ]
        ], 500);
    }

}
