<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Session;
use function mysql_xdevapi\getSession;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $requestId = $request->hasHeader('request-id')
            ? $request->header('request-id')
            : (string) Str::uuid();

        if ($request->hasHeader('x-api-token')) {
            $user = User::where('token', '=', $request->header('x-api-token'));

            if (!$user) {
                return response([
                    "request_id" => $requestId,
                    "status" => "error",
                    "code" => "30001",
                    "error_message" => "Неверный токен",
                ]);
            }

            if ($user->blocked) {
                return response([
                    "request_id" => $requestId,
                    "status" => "error",
                    "code" => "30002",
                    "error_message" => "Пользователь заблокирован",
                ]);
            }
            Session::flash('user', $user);

        } else {
            return response([
                "request_id" => $requestId,
                "status" => "error",
                "code" => "30001",
                "error_message" => "Токен отсутствует",
            ]);
        }

        return $next($request);
    }
}
