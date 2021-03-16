<?php

namespace App\Exceptions;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;
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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        if ($e instanceof AuthenticationException) {

            return $this->apiResponse(null, $e->getMessage(), Response::HTTP_UNAUTHORIZED);

        }

        if ($e instanceof NotFoundHttpException) {

            return $this->apiResponse(null, 'Incorrect route', Response::HTTP_NOT_FOUND);

        }

        if ($e instanceof QueryException) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == '1062') {
                return $this->apiResponse(null, 'Duplicate Entry', Response::HTTP_BAD_REQUEST);
            }
        }

        return parent::render($request, $e);

    }
}
