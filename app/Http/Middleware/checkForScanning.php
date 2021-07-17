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
        // is someone scanning?
        $scanning = Scan::whereNull('finished')->orderBy('id', 'asc')->first();

        dd($scanning);

        if ($request->session()->exists('scanning')) {
            $request->session()->forget('scanning');
        }
        if ($scanning) {
            $request->session(['scanning' => $scanning->user->name]);
        }

        return $next($request);
    }
}
