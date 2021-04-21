<?php

namespace App\Exceptions;

use Throwable;
use Util;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;

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
	 * @param  \Throwable  $exception
	 * @return void
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
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Throwable $exception)
	{
        $message = $exception->getMessage();
        if (empty($message)) $message = null;

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Unauthenticated',
				'errors' => [
					'type' => ['No autorizado para el recurso'],
				]
            ], 401);
        } elseif ($exception instanceof ModelNotFoundException or $exception instanceof NotFoundHttpException) {
            preg_match('/[^ ]*$/', $message, $id);
            if (count($id) > 0) {
                $id = $id[0];
                preg_match('#\[(.*?)\]#', $message, $model);
                if (count($model) > 1) {
                    $message = $model[1]::getTableName();
                    $message = ': ' . Util::translate($message) . ' ' . $id;
                } else {
                    $message = '';
                }
            } else {
                $message = '';
            }
			return response()->json([
				'message' => 'Data not found',
				'errors' => [
					'type' => ['Recurso no encontrado' . $message],
				]
			], 404);
		} elseif ($exception instanceof \PDOException) {
			$db_code = trim($exception->getCode());
			$code = 400;
			\Log::channel('error')->error('PDOException: ' . $db_code);
			switch (intval($db_code)) {
				case 23505:
					$error_message = 'Solicitud inválida';
					break;
				case 7:
					$error_message = 'Error en la conexión con el servidor';
					$code = 503;
					break;
				default:
					$error_message = 'Error en la base de datos';
					$code = 500;
			}
			return response()->json([
				'message' => 'Database error',
				'errors' => [
					'type' => [$message ?? $error_message],
				]
			], $code);
		} elseif ($exception instanceof HttpException) {
			switch ($exception->getStatusCode()) {
				case 401:
					return response()->json([
						'message' => 'Unauthorized',
						'exception' => $exception,
						'errors' => [
							'type' => [$message ?? 'Sesión caducada'],
						]
					], $exception->getStatusCode());
				case 403:
					return response()->json([
						'message' => 'Forbidden',
						'errors' => [
							'type' => [$message ?? 'No autorizado'],
						]
					], $exception->getStatusCode());
					break;
				case 409:
					return response()->json([
						'message' => 'Conflict',
						'errors' => [
							'type' => [$message ?? 'El registro ya existe'],
						]
					], $exception->getStatusCode());
					break;
				case 500:
					return response()->json([
						'message' => 'Internal Server Error',
						'exception' => $exception,
						'errors' => [
							'type' => [$message ?? 'Error interno del servidor'],
						]
					], $exception->getStatusCode());
					break;
				default:
					return response()->json([
						'message' => 'Internal Server Error',
						'exception' => $exception,
						'errors' => [
							'type' => [$message ?? 'Error ' . $exception->getStatusCode()],
						]
					], $exception->getStatusCode());
			}
		} elseif ($exception instanceof ValidationException) {
			return response()->json([
				'message' => 'Validation error',
				'errors' => $exception->errors()
			], 409);
		} else {
			\Log::channel('error')->error('Error inesperado: ' . $exception);
		}
		return parent::render($request, $exception);
	}
}
