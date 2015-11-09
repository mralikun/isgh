<?php namespace App\Http\Middleware;

use App\cycle;
use Closure;
use Illuminate\Auth\Guard;
use Illuminate\Support\Facades\Redirect;

class CycleExistence {

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

        if ($this->auth->guest() ||$this->auth->user()->role_id == 2 || $this->auth->user()->role_id == 3)
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


        if ( $this->auth->user()->role_id == 1)
        {
            if (!$request->ajax())
            {
                $latest_cycle = cycle::latest()->first();

                if(empty($latest_cycle)){
                    return redirect('/admin/cycle');
                }else{

                    $latest_cycle_end_date = $latest_cycle->end_date ;

                    // check if the end_date of the last cycle is older
                    if (strtotime($latest_cycle_end_date) < time()) {
                        // okay we need to create new cycle
                        return redirect('/admin/cycle');
                    }
                }
            }

        }

        return $next($request);
    }


}
