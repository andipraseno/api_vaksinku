<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ActasysCekToken
{
    public function handle($request, Closure $next)
    {
        // Mengambil token dari header
        $token = $request->header('Token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided',
                'data' => ''
            ], 401);
        }

        // Menghapus "Bearer " dari token jika ada
        $token = str_replace('Bearer ', '', $token);

        try {
            // $decoded = JWT::decode($token, new Key(config('app.jwt.key'), config('app.jwt.algo')));

            // // Memeriksa waktu kedaluwarsa token
            // if ($decoded->issue_expired < time()) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Token expired',
            //         'data' => ''
            //     ], 401);
            // }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data' => ''
            ], 401);
        }
    }
}
