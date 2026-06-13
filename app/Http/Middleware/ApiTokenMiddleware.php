<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        // No Authorization header at all
        if (empty($header)) {
            return response()->json([
                'status'  => false,
                'message' => 'Authorization token is missing. Please pass a Bearer token in the Authorization header.',
                'hint'    => 'Authorization: Bearer <your_token>',
            ], 401);
        }

        // Header present but not a Bearer token
        if (!str_starts_with($header, 'Bearer ')) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid Authorization format. Use: Authorization: Bearer <your_token>',
            ], 401);
        }

        $provided = trim(substr($header, 7));
        $expected = config('app.api_bearer_token');

        // Token present but incorrect
        if ($provided !== $expected) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid authorization token. Access denied.',
            ], 401);
        }

        return $next($request);
    }
}
