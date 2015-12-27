<?php namespace App\Http\Middleware;

use App\AssociateDirector;
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


        if ($this->auth->user()->role_id == 2 )
        {
            if ($request->ajax())
            {
                return response('false');
            }
            else
            {
                return redirect()->guest('/user/profile');
            }

        }elseif($this->auth->user()->role_id == 3){

            $user_data = AssociateDirector::whereid($this->auth->user()->user_id)->first();
            if(!empty($user_data)){

                if($user_data->reviewer == 0) {
                    return redirect()->guest('/user/profile');
                }
            }
        }


        return $next($request);
    }


}
