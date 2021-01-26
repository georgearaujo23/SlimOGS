<?php

namespace src;

use Tuupola\Middleware\JwtAuthentication;
use App\Middlewares\ {
    JwtDateTimneMiddleware
};
/**
 * Auth básica do JWT
 * Whitelist - Bloqueia tudo, e só libera os
 * itens dentro do "ignore"
 */
function jwtAuth(): JwtAuthentication
{
    return new JwtAuthentication([
        "regexp" => "/(.*)/", //Regex para encontrar o Token nos Headers - Livre
        "header" => "X-Token", //O Header que vai conter o token
        "path" => "/", //Vamos cobrir toda a API a partir do /
        "ignore" => ["/auth", "/refresh_token"],
        "realm" => "Protected", 
        "secret" => getenv('JWT_SECRET_KEY'),
        'attribute' => 'jwt'
    ]);
}