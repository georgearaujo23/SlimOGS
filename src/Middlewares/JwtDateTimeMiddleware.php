<?php

namespace App\Middlewares;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

final class JwtDateTimeMiddleware{
    
    public function __invoke(Request $request, Response $response, callable $next): Response{
        $token = $request->getAttribute('jwt');
        $validade = new \DateTime($token['expired_at']);
        $now = new \DateTime();
        if($validade < $now){
           return $response->withJson(json_encode(["message" => "Token vencido"],  256))
                    ->withHeader('Content-type', 'application/json')
                    ->withStatus(401);
        }
        $response = $next($request, $response);
        return $response;
    }
    
}
