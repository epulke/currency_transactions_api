<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

	public function render($request, Throwable $exception) {
		if ($exception instanceof ValidationException) {
			return response()->json(['error' => $exception->validator->errors()], 422);
		}

		if ($exception instanceof BadRequestHttpException) {
			return response()->json(['error' => $exception->getMessage()], $exception->getStatusCode());
		}

		if ($exception instanceof NotFoundHttpException) {
			return response()->json(['error' => 'The requested endpoint is not supported.'], 404);
		}

		if ($exception instanceof MethodNotAllowedHttpException) {
			return response()->json(['error' => 'This method is not allowed for this endpoint.'], 405);
		}

		return parent::render($request, $exception);
	}
}
