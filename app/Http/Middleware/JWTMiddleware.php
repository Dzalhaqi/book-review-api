<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;

class JwtMiddleware extends BaseMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    try {
      $user = JWTAuth::parseToken()->authenticate();
    } catch (Exception $e) {
      if ($e instanceof TokenInvalidException) {
        return response()->json([
          'status_code' => 403,
          'message' => 'Token is Invalid'
        ], 403);
      } else if ($e instanceof TokenExpiredException) {
        return response()->json([
          'status_code' => 401,
          'token_expired' => true,
          'message' => 'Token is Expired'
        ], 401);
      } else if ($e instanceof TokenBlacklistedException) {
        return response()->json([
          'status_code' => 400,
          'message' => 'Token is Blacklisted'
        ], 400);
      } else {
        return response()->json([
          'status_code' => 404,
          'message' => 'Authorization Token not found'
        ], 404);
      }
    }
    return $next($request);
    // return $next($request)->header('Authorization', 'Bearer ' . $token);
  }
}
