<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use App\Models\movie; 

class Authenticate extends BaseMiddleware
{
    public function handle($request, Closure $next, $guard = null)
    {
        $header = $request->header('Authorization');

        if (!$header || strpos($header, 'Bearer ') !== 0) {
            return response()->json([
                'status' => 'Unauthorized',
                'message' => 'Site 2 requires a valid JWT Bearer token.'
            ], 401);
        }

        try {
            // 1. Validate the token and get the payload
            $payload = JWTAuth::parseToken()->getPayload();
            
            // 2. Extract the User ID (sub) from the token
            $userId = $payload->get('sub');

        } catch (Exception $e) {
            return response()->json([
                'status' => 'Bearer Token Error', 
                'message' => 'Site 2 rejected the token: ' . $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
