<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Faculty
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $Faculty_route = $request->Route('fakultas');
        $Faculty = Auth::user()->Faculty_Id;
        if ($Faculty == null || $Faculty_route == null || $Faculty_route == 0) {
          return $next($request);
        }
        if ($Faculty_route == $Faculty) {
          return $next($request);
        }
        return response(view('403'));
    }
}
