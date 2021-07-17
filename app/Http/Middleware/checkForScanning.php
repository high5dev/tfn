<?php

namespace App\Http\Middleware;

use App\Models\Scan;
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

        // is someone scanning?
        $scanning = Scan::whereNull('finished')->orderBy('id', 'asc')->first();

        if ($scanning) {
            $request->session(['scanning' => $scanning->user->name]);
            dd($request->session()->all());
        }

        return $next($request);
    }
}
