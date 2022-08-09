<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $message = '';
        $code = '';

        try
        {
            JWTAuth::parsetoken()->authenticate();
            return $next($request);
        }
        catch (TokenExpiredException $e){
            $message = "Token expirado";
            $code = response()->setStatusCode(Response::HTTP_ACCEPTED)->get();
        }
        catch (TokenInvalidException $e){
            $message = "Token invalido";
        }
        catch (JWTException $e){
            $message = "Token erroneo";
        }

        return response()->json([
            'code' => $code,
            'status' => false,
            'message' => $message
        ])->setStatusCode(Response::HTTP_UNAUTHORIZED);
    }
}
