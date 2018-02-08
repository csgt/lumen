<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $mensaje = $e->getMessage();
        switch (true) {
            case $e instanceof ModelNotFoundException:
                $mensaje = ($mensaje?$mensaje:'Modelo no encontrado');
                $retval = $this->errorResponse($mensaje, 404);
                break;
            case $e instanceof AuthenticationException:
                $mensaje = ($mensaje?$mensaje:'Operación no permitida');
                $retval = $this->errorResponse($mensaje, 401);
                break;
            case $e instanceof NotFoundHttpException:
                $mensaje = ($mensaje?$mensaje:'Recurso no encontrado');
                $retval = $this->errorResponse($mensaje, 404);
                break;
            case $e instanceof ValidationException:
                $mensaje = '';
                foreach ($e->errors() as $key => $error) {
                    foreach ($error as $err) {
                        $mensaje .= $err . "\n";
                    }
                }
                $mensaje = ($mensaje?$mensaje:'Los datos no pasaron la validación');
                $retval = $this->errorResponse($mensaje, 422);
                break;
            default:
                $mensaje = ($mensaje?$mensaje:'Operación inválida' . json_encode($e));
                $retval = $this->errorResponse($mensaje, 400);
        }
        return $retval;

        return parent::render($request, $e);
    }

    protected function errorResponse($message, $statusCode=400)
    {
        return response()->json(['error' => $message], $statusCode);
    }
}
