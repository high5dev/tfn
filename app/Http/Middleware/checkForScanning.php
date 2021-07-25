<?php

namespace App\Http\Middleware;

use App\Models\Scan;
use Auth;
use Closure;
use Illuminate\Http\Request;

class checkForScanning
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
        // clear the session variable if it exists
        if ($request->session()->exists('scanning')) {
            $request->session()->forget('scanning');
        }

        // if the user is logged in...
        if (Auth::check()) {
            // is someone scanning?
            $scanning = Scan::whereNull('stopped')->orderBy('id', 'desc')->first();

            if ($scanning) {
                session(['scanning' => $scanning->user->name]);
            }
        }

        return $next($request);
    }
}
