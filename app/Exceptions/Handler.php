<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     */
    protected $dontReport = [
        // Add exceptions you don't want to log
    ];

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle Validation Exceptions
        if ($exception instanceof ValidationException) {
            return response()->error(
                __('validation.failed'),
                $exception->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // Handle Not Found Exceptions
        if ($exception instanceof NotFoundHttpException) {
            return response()->error(
                __('messages.not_found'),
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        // Handle Not Found Exceptions
        if ($exception instanceof ModelNotFoundException) {
            return response()->error(
                __('messages.not_found'),
                null,
                Response::HTTP_NOT_FOUND
            );
        }

        // Handle Unauthorized Access
        if ($exception instanceof AuthorizationException) {
            return response()->error(
                __('auth.unauthorized'),
                null,
                Response::HTTP_FORBIDDEN
            );
        }

        // Handle Uan Access
        if ($exception instanceof AuthenticationException) {
            return response()->error(
                __('auth.unauthenticated'),
                null,
                Response::HTTP_UNAUTHORIZED
            );
        }

        // if ($exception instanceof QueryException) {
        //     // Handle all other exceptions
        //     return response()->error(
        //         __('An unexpected database error occurred.'),
        //         null,
        //         Response::HTTP_INTERNAL_SERVER_ERROR
        //     );
        // }

        // Handle all other exceptions
        // return response()->error(
        //     __('An unexpected error occurred.'),
        //     null,
        //     Response::HTTP_INTERNAL_SERVER_ERROR
        // );


        // Default Exception Handling
        return parent::render($request, $exception);
    }
}
