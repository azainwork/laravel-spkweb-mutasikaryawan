<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            Log::warning('Unauthorized access attempt: No user found');
            return redirect()->route('login');
        }

        if ((string) $user->role_id !== $role) {
            Log::warning('Unauthorized access attempt', [
                'user_id' => $user->id,
                'user_role_id' => $user->role_id,
                'required_role_id' => $role
            ]);
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}
