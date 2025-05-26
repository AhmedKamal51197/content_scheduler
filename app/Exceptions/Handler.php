<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    /**
     * to ensure default exception handlling
     */
    public function render($request, Throwable $exception)
    {
        //Handle ModelNotFoundException
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
                'error' => $exception->getMessage()
            ], 404);
        }
         //  Handle unsupported HTTP methods
         if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'The requested method is not supported for this route.',
                'message' => 'Route NotFound.',
            ], 405);
        }
        // Handle other exceptions
        return parent::render($request, $exception);
    }
}
