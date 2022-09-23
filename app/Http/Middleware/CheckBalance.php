<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckBalance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    const MIN_VAL = 10;
    public function handle(Request $request, Closure $next)
    {
        $userBalance = User::find(auth()->id())->getWalletBalance();
        if($userBalance < self::MIN_VAL){
            return back()->withErrors('No tienes saldo suficiente');
        }
        return $next($request);
    }
}
