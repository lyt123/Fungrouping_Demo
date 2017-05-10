<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        BaseException::class,
        JWTException::class
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
        return parent::report($e);
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
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }
//        if (config('app.debug')) {
//            return parent::render($request, $e);
//        }

        return $this->handle($e);
    }


    /**
     * Description : 自定义异常处理
     * Author : Benson
     *
     * @param Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function handle(Exception $e)
    {
        $status = method_exists($e, 'getStatusCode')? $e->getStatusCode(): 500;
        $data = [
            'status' => $status
        ];

        $is_debug = config('app.debug');
        $inform = $e->getMessage() ?: trans('tips.'.$status);

        //TODO:得到异常消息
        if(!($data['inform'] = $inform)) {
            if ($e instanceof BaseException)
                $data['inform'] = $e->getError();
            else {
                if ($is_debug)
                    $data['inform'] = $e->getMessage();
                else
                    $data['inform'] = trans('tips.500');
            }
        }

        //TODO:得到异常位置
        if ($is_debug) {
            if($e instanceof BaseException)
                $data['position'] = $e->getPosition();
            else
                $data['position'] = get_class($e)
                    . ' In ' . $e->getFile()
                    . ' Line ' . $e->getLine();
        }

        if(env('APP_DEBUG', false))
            $data['sql'] = \App\Providers\AppServiceProvider::$sql_listen;

        return response()->json($data, $status);
    }
}
