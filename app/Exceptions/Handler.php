<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Symfony\Component\ErrorHandler\Error\FatalError;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->renderable(function (FatalError $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['status' => false, 'message' => trans('label.record_not_found_error_msg')], 200);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['status' => false, 'message' => trans('label.record_not_found_error_msg')], 200);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['status' => false, 'message' => trans('label.invalid_method_error_message')], 200);
            }
        });

        $this->renderable(function (PostTooLargeException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['status' => false, 'message' => trans('label.max_file_size_error_msg')], 200);
            }
        });

        $this->renderable(function (TooManyRequestsHttpException $e, $request) {
            if ($request->route()->getAction('as') === 'login') {
                if(isset($request->email) && !empty($request->email)){ //If login with email attempt
                    event(new Lockout($request));
                }
                $trottleKey = Str::transliterate(request()->ip());
                $seconds = RateLimiter::availableIn($trottleKey);
                return response()->json(['status' => false, 'message' => trans('label.throttle', ['minutes' => ceil($seconds / 60)])], 200);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
