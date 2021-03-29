<?php

namespace App\Http\Middleware;

use App\Helpers\JwtAuth;
use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /* comprobar si el usuario está identificado */
        $token = $request->header('Authorization');
        $jwtAuth = new JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // var_dump($checkToken); die();

        if($checkToken){

            return $next($request);


        }else{
            $data = [
                'code'=> 404,
                'status'=> 'error',
                'message'=> 'El usuario no está identificado.'
             ];
             
             return response()->json($data, $data['code']);

            }
            
        


    }
}
