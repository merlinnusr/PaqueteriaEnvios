<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CacheKiller
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
        $cachedViewsDirectory = app('path.storage') . '/framework/views/';

        if ($handle = opendir($cachedViewsDirectory)) {

            while (false !== ($entry = readdir($handle))) {
                if (strstr($entry, '.')) continue;
                @unlink($cachedViewsDirectory . $entry);
            }

            closedir($handle);
        }

        return $next($request);
    }
}
