<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 201);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        \Log::info('=== LOGIN ATTEMPT ===', [
            'email' => $request->input('email'),
            'has_password' => !empty($request->input('password'))
        ]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Login through external API
        $apiUrl = env('API_URL', '');
        
        if (!empty($apiUrl)) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($apiUrl . '/auth/login', [
                        'email' => $request->email,
                        'password' => $request->password,
                    ]);

                if ($response->successful()) {
                    return response()->json($response->json());
                }

                return response()->json([
                    'status' => 'error',
                    'message' => $response->json()['message'] ?? 'Authentication failed',
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to connect to authentication service: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Fallback to local JWT authentication
        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => auth('api')->user(),
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Store JWT token in session for subsequent requests
     */
    public function setSession(Request $request)
    {
        // dd($request);
        $token = $request->input('token');
        $user = $request->input('user');
        \Log::info('setSession called', [
            'has_token' => !empty($token),
            'token_preview' => $token ? substr($token, 0, 20) . '...' : 'null',
            'has_user' => !empty($user),
            'session_id' => $request->session()->getId()
        ]);
        
        if ($token) {
            $request->session()->put('token', $token);
            if ($user) {
                $request->session()->put('user', $user);
            }
            // Verify token was stored
            \Log::info('Token stored in session', [
                'stored_token' => $request->session()->get('token') ? 'confirmed' : 'FAILED'
            ]);
            
            return response()->json(['status' => 'success']);
        }
        
        \Log::warning('setSession - no token provided');
        return response()->json(['status' => 'error', 'message' => 'No token provided'], 400);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $apiUrl = env('API_URL', '');
        
        if (!empty($apiUrl)) {
            try {
                $token = $request->bearerToken();
                
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ])
                    ->post($apiUrl . '/auth/logout');
                if ($response->successful()) {
                    return response()->json($response->json());
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Logout failed',
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to logout: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Fallback to local JWT
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        $apiUrl = env('API_URL', '');
        
        if (!empty($apiUrl)) {
            try {
                $token = $request->bearerToken();
                
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ])
                    ->post($apiUrl . '/auth/refresh');

                if ($response->successful()) {
                    return response()->json($response->json());
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Token refresh failed',
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to refresh token: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Fallback to local JWT
        return response()->json([
            'status' => 'success',
            'user' => auth('api')->user(),
            'authorization' => [
                'token' => auth('api')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        $apiUrl = env('API_URL', '');
        
        if (!empty($apiUrl)) {
            try {
                $token = $request->bearerToken();
                
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ])
                    ->get($apiUrl . '/auth/me');

                if ($response->successful()) {
                    return response()->json($response->json());
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to get user info',
                ], $response->status());

            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to connect to service: ' . $e->getMessage(),
                ], 500);
            }
        }

        // Fallback to local JWT
        return response()->json([
            'status' => 'success',
            'user' => auth('api')->user(),
        ]);
    }
}
