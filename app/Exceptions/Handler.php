<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        // if($request->is('api/*')){
        //     return response()->json([
        //         'result' => 3000,
        //         'message' => $exception->getMessage(),
        //         'data' => []
        //     ]);
        // }

        // if ($request->is('api/*')){
        //     $status = $exception->getStatusCode()?$exception->getStatusCode():$exception->getCode();
        //     return response()->json([
        //                             "code"			=>	$status,
        //                             "message"		=>	$exception->getMessage(),
        //                             "data"			=> 	[]
        //                         ], $status)
        //                         ->header('Content-Type', 'application/vnd.api+json');
        // }


        return parent::render($request, $exception);
    }
}
