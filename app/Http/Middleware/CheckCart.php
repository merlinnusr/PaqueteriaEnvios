<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCart
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if( !empty(session('cart')) && null !== (session('cart')))  {

            return $next($request);
        }
        return redirect()->route('home');

    }
}
