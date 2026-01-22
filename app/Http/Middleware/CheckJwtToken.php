<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;  
use Illuminate\Support\Facades\Log;

class CheckJwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if token exists in session first (most reliable), then cookie, header
        \Log::info('=== JWT Middleware Debug ===', [
            'url' => $request->url(),
            'session_id' => $request->session()->getId(),
            'session_token' => $request->session()->get('token') ? 'exists' : 'empty',
            'session_all' => $request->session()->all(),
            'cookie_token' => $request->cookie('token') ? 'exists' : 'empty',
            'bearer_token' => $request->bearerToken() ? 'exists' : 'empty',
        ]);

        $token = $request->session()->get('token')
              ?? $request->bearerToken()
              ?? $request->header('X-Auth-Token')
              ?? $request->cookie('token');

        if (!$token) {
            \Log::warning('No token found - redirecting to login');
            // For web routes, redirect to login
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        \Log::info('Token found', ['token_length' => strlen($token), 'source' => $request->session()->get('token') ? 'session' : 'other']);

        // Store token in session for subsequent requests
        $request->session()->put('token', $token);
        
        // Add token to request for use in controllers
        $request->merge(['jwt_token' => $token]);

        return $next($request);
    }
}
