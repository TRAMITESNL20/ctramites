<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use App\Models\User;

class BasicAuth extends AuthenticateWithBasicAuth
{
    public function __construct(AuthFactory $auth) {
        parent::__construct($auth);
    }

    public function handle($request, Closure $next, $guard = null, $field = null)
    {
        // User::where('email', '')
        dd($field);
        $this->auth->guard($guard)->basic($field ?: 'email');
        return $next($request);
    }
}