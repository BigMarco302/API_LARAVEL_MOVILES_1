<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateJsonApiHeaders
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

        
        // verifica en todas las peticiones uqe tengan el header ACCept 
        if ($request->header('Accept') !== 'application/vnd.api+json') {
            throw new HttpException(406, __('Not Acceptable'));
        }   
        // // Verificar la cabecera 'Content-Type' solo en solicitudes POST Y PATCH
        if ($request->isMethod('POST') || $request->isMethod('PATCH')) {
            if ($request->header('content-Type') !== 'application/vnd.api+json') {
                throw new HttpException(415, __('Unsupported Media Type'));
            } 
         }


         $response = $next($request);

         // Asegúrate de que la respuesta sea una instancia de Response
         if ($response instanceof Response) {
             // Aplica el encabezado 'Content-Type' a la respuesta
             $response->header('Content-Type', 'application/vnd.api+json');
         }
 
         return $response;
     
    }
}
