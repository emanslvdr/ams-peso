<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next, $role): Response
    // {
    //     if ($request->user()->role !== $role) {
    //         return redirect()->route('dashboard');
    //     }
    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next, $role)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Check if the user's role matches the required role
        if ($request->user()->role !== $role) {
            // Handle the redirect for different roles
            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Redirect admin to their dashboard
            } elseif ($request->user()->role === 'client') {
                return redirect()->route('client.dashboard'); // Redirect client to their dashboard
            } else {
                return redirect()->route('home'); // Default redirect for other roles (e.g., user)
            }
        }

        // If the user has the correct role, allow the request to continue
        return $next($request);
    }
}
