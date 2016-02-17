<?php namespace App\Http\Middleware;

use App\AssociateDirector;
use App\Cycle;
use Closure;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CycleExistence
{

    protected $auth;


    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('false');
            } else {
                return redirect()->guest('/');
            }
        } elseif ($this->auth->user()->role_id == 3) {
            if ($request->ajax()) {
                return $next($request);
            } else {
                $latest_cycle = Cycle::latest()->first();
                if(empty($latest_cycle)){
                    $user_data = AssociateDirector::whereid($this->auth->user()->user_id)->first();

                    if ($user_data->reviewer == 0) {
                        // he is not a reviewer send him to the no cycle page if there is no cycle
                        $url = \Illuminate\Support\Facades\Request::url();

                        if (strpos($url, 'no_cycle_yet') !== false) {
                            return $next($request);
                        } else {
                            return redirect('/no_cycle_yet');
                        }
                    } else {
                        $latest_cycle = Cycle::latest()->first();
                        if(empty($latest_cycle)){
                            return redirect('/admin/cycle');
                        }
                    }
                }


                $url = \Illuminate\Support\Facades\Request::url();

                if (strpos($url, 'user/changePassword') !== false || strpos($url, 'user/changePass') !== false) {
                    return $next($request);
                } else {
                    // in this section I'am going to check if this is his first time to access the site or not
                    $password_changed = $this->auth->user()->passwordchanged;

                    if ($password_changed == 1) {
                        $user_data = AssociateDirector::whereid($this->auth->user()->user_id)->first();

                        if (!empty($user_data)) {

                            if ($user_data->reviewer == 0) {
                                return $next($request);
                            } else {

                                if ($user_data->reviewer == 0) {
                                    //->guest('/user/profile');
                                } else {
                                    if (!$request->ajax()) {
                                        $latest_cycle = Cycle::latest()->first();

                                        if (empty($latest_cycle)) {
                                            return redirect('/admin/cycle');
                                        } else {

                                            $latest_cycle_end_date = $latest_cycle->end_date;
                                            // check if the end_date of the last cycle is older
                                            if (strtotime($latest_cycle_end_date) - time() <= 2592000) {
                                                // okay we need to create new cycle
                                                return redirect('/admin/cycle');
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        return redirect('/user/changePassword');
                    }
                }
            }


        } elseif ($this->auth->user()->role_id == 2) {

            $latest_cycle = Cycle::latest()->first();

            if (empty($latest_cycle)) {
                $url = \Illuminate\Support\Facades\Request::url();

                if (strpos($url, 'no_cycle_yet')) {
                    return $next($request);
                }else{
                    return redirect('/no_cycle_yet');
                }
            }

            if ($request->ajax()) {
                return $next($request);
            } else {
                $url = \Illuminate\Support\Facades\Request::url();
                if (strpos($url, 'user/changePassword') !== false || strpos($url, 'user/changePass') !== false) {
                    return $next($request);
                } else {
                    // in this section I'am going to check if this is his first time to access the site or not
                    $password_changed = $this->auth->user()->passwordchanged;
                    if ($password_changed == 1) {
                        return $next($request);
                    } else {
                        return redirect('/user/changePassword');
                    }
                }
            }

        }


        if ($this->auth->user()->role_id == 1) {
            if (!$request->ajax()) {
                $latest_cycle = Cycle::latest()->first();

                if (empty($latest_cycle)) {
                    return redirect('/admin/cycle');
                } else {

                    $latest_cycle_end_date = $latest_cycle->end_date;

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
