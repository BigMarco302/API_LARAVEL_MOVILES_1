<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Asegúrate de importar Illuminate\Http\Response
use Symfony\Component\HttpKernel\Exception\HttpException;

class ValidateHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {

       // verifica en todas las peticiones que tengan el header 'Accept'
       if ($request->header('Accept') !== 'application/vnd.api+json') {
            throw new HttpException(406, __('Not Acceptable'));
        }   
        // Verificar la cabecera 'Content-Type' solo en solicitudes POST Y PATCH
        if ($request->isMethod('POST') || $request->isMethod('PATCH')) {
            if ($request->header('content-Type') !== 'application/vnd.api+json') {
                throw new HttpException(415, __('Unsupported Media Type'));
            } 
        }

        $response = $next($request);
        $response->header('Content-Type', 'application/vnd.api+json');
        return $response;
 
    }
}
