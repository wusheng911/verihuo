<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;

use App\Services\Helpers;

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
        return parent::render($request, $e);
    }
    
    /**
     * Convert the given exception into a Response instance.
     *
     * @param \Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    protected function convertExceptionToResponse(Exception $e)
    {
        $debug = config('app.debug', false);

        if ($debug) {
            return (new SymfonyDisplayer($debug))->sendPhpResponse($e);
        }
           
        if (get_class($e) == 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException') {
            return redirect(Helpers::getHomeUrl(Helpers::getPlatform()));
        }

        if(Helpers::isMobile()){
            return response()->view('errors.default', ['exception' => $e], 500);
        }else{
            return response()->view('errors.pc', ['exception' => $e], 500);
        }
    } 
     
       
}
