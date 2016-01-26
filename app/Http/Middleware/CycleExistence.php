<?php namespace App\Http\Middleware;

use App\AssociateDirector;
use App\Cycle;
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

        if ($this->auth->guest())
        {
            if ($request->ajax())
            {
                return response('false');
            }
            else
            {
                return redirect()->guest('/');
            }
        }elseif($this->auth->user()->role_id == 3){

            $user_data = AssociateDirector::whereid($this->auth->user()->user_id)->first();

            if(!empty($user_data)){

                if($user_data->reviewer == 0) {

                }else{

                        if($user_data->reviewer == 0) {
                             //->guest('/user/profile');
                        }else{
                            if (!$request->ajax())
                            {
                                $latest_cycle = Cycle::latest()->first();

                                if(empty($latest_cycle)){
                                    return redirect('/admin/cycle');
                                }else{

                                    $latest_cycle_end_date = $latest_cycle->end_date ;
                                    // check if the end_date of the last cycle is older
                                    if (strtotime($latest_cycle_end_date) - time() <= 1296000) {
                                        // okay we need to create new cycle
                                        return redirect('/admin/cycle');
                                    }

                                }
                            }
                        }
                }
            }
        }elseif($this->auth->user()->role_id == 2){
            return $next($request);
        }


        if ( $this->auth->user()->role_id == 1)
        {
            if (!$request->ajax())
            {
                $latest_cycle = Cycle::latest()->first();

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
