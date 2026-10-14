<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Semua error pada route /api/* dikembalikan dalam format JSON yang seragam
        if ($request->is('api/*') || $request->is('api')) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
                $message = $exception->getMessage() ?: ($status === 404 ? 'Endpoint tidak ditemukan' : 'HTTP error');

                return response()->json(['success' => false, 'message' => $message], $status);
            }

            return response()->json([
                'success' => false,
                'message' => env('APP_DEBUG') ? $exception->getMessage() : 'Terjadi kesalahan pada server',
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
