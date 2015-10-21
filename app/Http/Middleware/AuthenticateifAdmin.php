<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Guard;
use Illuminate\Support\Facades\Redirect;

class AuthenticateifAdmin {

    protected $auth;


    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->guest() )
        {
            if ($request->ajax())
            {
                return response('false');
            }
            else
            {
                return redirect()->guest('/');
            }
        }


        if ($this->auth->user()->role_id == 2 || $this->auth->user()->role_id == 3)
        {
            if ($request->ajax())
            {
                return response('false');
            }
            else
            {
                return redirect()->guest('/user/profile');
            }
        }


        return $next($request);
    }


}
