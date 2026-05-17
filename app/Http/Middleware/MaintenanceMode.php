<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for admin users
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Skip for auth routes & admin paths
        if ($request->routeIs('login') || $request->routeIs('logout') || $request->is('login') || $request->is('logout') || $request->is('admin/*')) {
            return $next($request);
        }

        // Read fresh from DB every request
        $isMaintenanceOn = \App\Models\Setting::get('maintenance_mode', '0');

        if ($isMaintenanceOn === '1') {
            $message = \App\Models\Setting::get(
                'maintenance_message',
                'Sistem sedang dalam pemeliharaan.'
            );
            return response()->view('maintenance', compact('message'), 503);
        }

        return $next($request);
    }
}
